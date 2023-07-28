<?php

namespace DBShenker\DTO;

final class NameAndAddress
{
    private ?string $contactName = null;
    private ?string $contactSiret = null;
    private ?string $address = null;
    private ?float $latitude = null;
    private ?float $longitude = null;

    private array $communicationMeans = [];

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