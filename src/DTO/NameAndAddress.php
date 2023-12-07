<?php

namespace DBShenker\DTO;

use DBShenker\Enum\NameAndAddressType;

final class NameAndAddress
{
    private NameAndAddressType $type;
    private ?string $addressLabel = null;
    private ?string $contactName = null;
    private ?string $contactSiret = null;
    private ?string $address = null;
    private ?float $latitude = null;
    private ?float $longitude = null;

    private array $communicationMeans = [];

    /**
     * @return NameAndAddressType
     */
    public function getType(): NameAndAddressType
    {
        return $this->type;
    }

    /**
     * @param NameAndAddressType $type
     * @return NameAndAddress
     */
    public function setType(NameAndAddressType $type): NameAndAddress
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddressLabel(): ?string
    {
        return $this->addressLabel;
    }

    /**
     * @param string|null $addressLabel
     * @return NameAndAddress
     */
    public function setAddressLabel(?string $addressLabel): NameAndAddress
    {
        $this->addressLabel = $addressLabel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    /**
     * @param string|null $contactName
     * @return NameAndAddress
     */
    public function setContactName(?string $contactName): NameAndAddress
    {
        $this->contactName = $contactName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactSiret(): ?string
    {
        return $this->contactSiret;
    }

    /**
     * @param string|null $contactSiret
     * @return NameAndAddress
     */
    public function setContactSiret(?string $contactSiret): NameAndAddress
    {
        $this->contactSiret = $contactSiret;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return NameAndAddress
     */
    public function setAddress(?string $address): NameAndAddress
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     * @return NameAndAddress
     */
    public function setLatitude(?float $latitude): NameAndAddress
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     * @return NameAndAddress
     */
    public function setLongitude(?float $longitude): NameAndAddress
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return array
     */
    public function getCommunicationMeans(): array
    {
        return $this->communicationMeans;
    }

    /**
     * @param array $communicationMeans
     * @return NameAndAddress
     */
    public function setCommunicationMeans(array $communicationMeans): NameAndAddress
    {
        $this->communicationMeans = $communicationMeans;
        return $this;
    }
}
