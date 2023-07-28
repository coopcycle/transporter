<?php

namespace DBShenker;

use DBShenker\DTO\CommunicationMean;
use DBShenker\DTO\Date;
use DBShenker\DTO\Mesurement;
use DBShenker\DTO\NameAndAddress;
use DBShenker\DTO\Package;
use DBShenker\Enums\CommunicationMeanType;
use DBShenker\Enums\DateEventType;
use DBShenker\Enums\NameAndAddressType;
use DBShenker\Enums\ProductType;
use DBShenker\Enums\QuantityType;
use DBShenker\Enums\QuantityUnitType;

/**
 * Class DBShenkerParser
 *
 *  Parse SCONTR file
 */
class DBShenkerParser
{

    private ?array $scontr = null;

    public function __construct(
        private readonly ?AddressGeocoder $addressGeocoder = null
    )
    { }

    /**
     * Parse SCONTR file or string
     *
     * @param array $scontr
     * @return void
     */
    public function parse(array $scontr): void
    {
        $this->scontr = $scontr;
    }

    /**
     * Parse RFF segment
     *
     * @return string
     */
    public function getID(): string
    {
        return $this->scontr['reference']['referenceID'];
    }

    /**
     * Parse GID segment
     *
     * @return array
     */
    public function getPackages(): array
    {
        //TODO: Handle Group9 and Group10 MSE segments
        $packages = array_filter($this->scontr['productGroup'], function ($v, $k) {
            return str_starts_with($k, 'quantity') && is_array($v);
        }, ARRAY_FILTER_USE_BOTH);

        return array_reduce($packages, function ($acc, $v) {
            $acc[] = new Package(
                type: ProductType::from(intval($v['unit'])),
                quantity: intval($v['quantity'])
            );
            return $acc;
        }, []);
    }

    /**
     * Parse MSE segment
     *
     * @return array
     */
    public function getMesurements(): array
    {
        //TODO: Handle array of mesurements
        //TODO: Handle Group9 and Group10 MSE segments
        return [
            new Mesurement(
                type: QuantityType::from($this->scontr['measurement']['quantityType']),
                quantity: intval($this->scontr['measurement']['quantity']['quantity']),
                unit: QuantityUnitType::from($this->scontr['measurement']['quantity']['unit'])
            ),
        ];
    }

    /**
     * Parse NAD segment
     *
     * @param NameAndAddressType $type
     * @return array|null
     */
    public function getNameAndAddress(NameAndAddressType $type, bool $geocode = false): NameAndAddress
    {

        //TODO: Handle Group1 NAD segment

        $nad = new NameAndAddress();

        foreach ($this->scontr['GR8'] as $v) {
            if ($v['nameAndAddress']['quality'] === $type->value) {
                $nad->setAddress(
                    trim(join("\n", array_slice($v['nameAndAddress'], 4)))
                );

                if ($geocode && $this->addressGeocoder instanceof AddressGeocoder) {
                    $res = $this->addressGeocoder->geocode($nad->getAddress());
                    if ($res) {
                        $nad->setLatitude($res['latitude'])->setLongitude($res['longitude']);
                    }
                }

                if (isset($v['communicationMeans'])) {
                    $nad->setContactName(
                        trim(join(' ', $v['communicationMeans']['contact']))
                    );

                    $nad->setCommunicationMeans($this->getCommunicationMeans($v['communicationMeans']));
                }

                /*
                 * NOT SURE IF THIS IS NECESSARY
                if (isset($v['Date'])) {
                    $date = new Date(
                        DateEventType::from($v['Date']['event']),
                        \DateTime::createFromFormat('ymd', $v['Date']['date'])
                            ->setTime(0,0));
                    $nad->addDate($date);
                }
                */
            }
        }
        return $nad;
    }

    public function getDate(DateEventType $type): ?Date
    {
        //TODO: Check for Group1 and Group12 DTM segments
        foreach ($this->scontr['GR8'] as $v) {
            if (!isset($v['Date'])) {
                continue;
            }
            if ($v['Date']['event'] === $type->value) {
                return new Date(
                    event: DateEventType::from($v['Date']['event']),
                    date: \DateTime::createFromFormat('ymd', $v['Date']['date'])
                        ->setTime(0,0));
            }
        }
        return null;
    }

    public function getComments(): array
    {
        //TODO: Handle multiple comments
        return [
            $this->scontr['text']['text'],
        ];
    }

    private function getCommunicationMeans(array $contactMeans): array {
        return array_values(array_filter(array_map(function ($contact) {
            if ($this->isPhone($contact)) {
                $contact = str_replace(['.', ' ', '-', '_', '/'], '', $contact);
                return new CommunicationMean(CommunicationMeanType::PHONE, $contact);
            }

            if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                return new CommunicationMean(CommunicationMeanType::EMAIL, strtolower($contact));
            }

            return null;
        }, array_map(function($v) { return $v[0]; },
            array_slice($contactMeans, 4)))));
    }


    private function isPhone(string $phone): bool
    {
        return preg_match('/^\d{2}.?\d{2}.?\d{2}.?\d{2}.?\d{2}$/', $phone) === 1;
    }



}