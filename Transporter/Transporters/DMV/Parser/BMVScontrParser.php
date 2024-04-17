<?php

namespace Transporter\Transporters\DMV\Parser;

use Transporter\DTO\CommunicationMean;
use Transporter\DTO\Date;
use Transporter\DTO\Mesurement;
use Transporter\DTO\NameAndAddress;
use Transporter\DTO\Package;
use Transporter\Enum\CommunicationMeanType;
use Transporter\Enum\DateEventType;
use Transporter\Enum\NameAndAddressType;
use Transporter\Enum\ProductType;
use Transporter\Enum\QuantityType;
use Transporter\Enum\QuantityUnitType;
use Transporter\Interface\TransporterParserInterface;
use Transporter\Transporters\DBSchenker\DTO\DBSchenkerPoint;
use Transporter\Transporters\DBSchenker\Enum\DBSchenkerProductClass;
use Transporter\Transporters\DMV\DTO\BMVPoint;
use Transporter\Transporters\DMV\Enum\BMVProductClass;
use Transporter\Utils\AddressGeocoder;

class BMVScontrParser implements TransporterParserInterface
{

    private array $scontr;

    private ?AddressGeocoder $addressGeocoder = null;

    public function parse(array $message): void
    {
        $this->scontr = $message;
    }

    public function __getScontr(): array
    {
        return $this->scontr;
    }


    public function getTasks(): array
    {
        return array_reduce($this->scontr['GR7'], function ($acc, $v) {
            $acc[] = $this->gr7ed($v);
            return $acc;
        }, []);
    }

    /**
     * @param AddressGeocoder|null $addressGeocoder
     */
    public function setAddressGeocoder(?AddressGeocoder $addressGeocoder): void
    {
        $this->addressGeocoder = $addressGeocoder;
    }

    private function gr7ed(array $gr7): BMVPoint
    {
        $point = new BMVPoint(
            id: self::getID($gr7),
            namesAndAddresses: self::getNamesAndAddresses($gr7, $this->addressGeocoder),
            dates: self::getDates($gr7),
            mesurements: self::getMesurements($gr7),
            packages: self::getPackages($gr7),
            comments: self::getComments($gr7)
        );
        $point->setProductClass(self::getProductClass($gr7));
        return $point;
    }

    /**
     * Parse RFF segment
     *
     * @param $message
     * @return string
     */
    private static function getID($message): string
    {
        return $message['reference']['referenceID'];
    }

    /**
     * Parse GID segment
     *
     * @param array $message
     * @return Package[]
     */
    private static function getPackages(array $message): array
    {
        //TODO: Handle Group9 and Group10 MSE segments
        $packages = array_filter($message['productGroup'], function ($v, $k) {
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
     * @param array $message
     * @return Mesurement[]
     */
    private static function getMesurements(array $message): array
    {
        //TODO: Handle Group9 and Group10 MSE segments
        return array_map(function($mes){
           return new Mesurement(
               type: QuantityType::from($mes['quantityType']),
               quantity: intval($mes['quantity']['quantity']),
               unit: QuantityUnitType::from($mes['quantity']['unit'])
           );
        }, normalize_depth($message['measurement']));
    }

    /**
     * Parse NAD segment
     *
     * @param array $message
     * @param AddressGeocoder|null $geocode
     * @return NameAndAddress[]
     */
    private static function getNamesAndAddresses(array $message, ?AddressGeocoder $geocode = null): array
    {

        //TODO: Handle Group1 NAD segment
        return array_reduce($message['GR8'], function ($acc, $nad) use (&$geocode) {
            $ret = new NameAndAddress();
            $ret->setType(NameAndAddressType::from($nad['nameAndAddress']['quality']));

            if (is_array($nad['nameAndAddress']['emmetName'])) {
                $ret->setAddressLabel(join(" ", $nad['nameAndAddress']['emmetName']));
            } else {
                $ret->setAddressLabel($nad['nameAndAddress']['emmetName']);
            }

            //print_r(array_merge([], ...array_values(array_slice($nad['nameAndAddress'], 4))));
            //TODO: Enjoy the ugly hack
            $address = [];
            $data = array_values(array_slice($nad['nameAndAddress'], 7));
            array_walk_recursive($data, function ($v) use (&$address) {
                $address[] = trim($v);
            });
            $address = join(" ", array_filter($address));
            // Set address
            $ret->setAddress($address);

            // Apply geocode if needed
            if ($geocode instanceof AddressGeocoder) {
                $res = $geocode->geocode($ret->getAddress());
                if ($res) {
                    $ret->setLatitude($res['latitude'])->setLongitude($res['longitude']);
                }
            }

            // Parse communication means
            if (isset($nad['communicationMeans'])) {
                $ret->setContactName(
                    match (gettype($nad['communicationMeans']['contact'])) {
                        'array' => trim(join(' ', $nad['communicationMeans']['contact'])),
                        'string' => trim($nad['communicationMeans']['contact']),
                        'NULL' => null
                    }
                );
                $ret->setCommunicationMeans(self::getCommunicationMeans($nad['communicationMeans']));
            }
            $acc[] = $ret;
            return $acc;
        }, []);
    }

    /**
     * @param array $message
     * @return Date[]
     */
    private static function getDates(array $message): array
    {

        return array_reduce($message['GR8'], function ($acc, $date) {
            if (isset($date['Date'])) {

                if (isset($date['Date']['event']) && isset($date['Date']['date'])) {
                    $acc[] = self::parseDate($date['Date']['event'], $date['Date']['date']);
                    return $acc;
                }

                if (
                    is_array($date['Date']) &&
                        count($date['Date']) > 0 &&
                        isset($date['Date'][0]) &&
                        is_array($date['Date'][0])
                ) {
                    $dates = array_map(function($v) {
                        return self::parseDate($v['event'], $v['date'], $v['hour'] ?? "0000");
                    }, $date['Date']);
                    $acc = array_merge($acc, $dates);
                }
            }
            return $acc;
        }, []);
    }

    private static function parseDate(string $event, string $date, string $hour = "0000"): Date
    {
        return new Date(
            event: DateEventType::from($event),
            date: \DateTime::createFromFormat('ymd Hi', sprintf('%s %s', $date, $hour))
        );
    }


    /**
     * @param array $message
     * @return BMVProductClass|null
     */
    private static function getProductClass(array $message): ?BMVProductClass
    {
        if (isset($message['productType'])) {
            return BMVProductClass::from(
                $message['productType']['regime'],
                $message['productType']['productType']
            );
        } else {
            return BMVProductClass::UNKNOWN;
        }
    }

    /**
     * @param $message
     * @return string|null
     */
    private static function getComments($message): ?string
    {
        if (
            !isset($message['text'])
            || !isset($message['text']['text'])
        ) {
            return null;
        }
        //TODO: Handle multiple comments
        return $message['text']['text'];
    }

    /**
     * @param array $contactMeans
     * @return CommunicationMean[]
     */
    private static function getCommunicationMeans(array $contactMeans): array {
        return array_values(array_filter(array_map(function ($contact) {
            if (self::isPhone($contact)) {
                $contact = str_replace(['.', ' ', '-', '_', '/'], '', $contact);
                return new CommunicationMean(CommunicationMeanType::PHONE, $contact);
            }

            if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                return new CommunicationMean(CommunicationMeanType::EMAIL, strtolower($contact));
            }

            return null;
        }, array_map(function($v) { return $v[0]; },
            array_slice($contactMeans, 5)))));
    }

    /**
     * @param string $phone
     * @return bool
     */
    private static function isPhone(string $phone): bool
    {
        return preg_match('/^\d{2}.?\d{2}.?\d{2}.?\d{2}.?\d{2}$/', $phone) === 1;
    }
}
