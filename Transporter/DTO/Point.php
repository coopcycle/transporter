<?php

namespace Transporter\DTO;

use Transporter\Enum\DateEventType;
use Transporter\Enum\INOVERTMessageType;
use Transporter\Enum\NameAndAddressType;

abstract class Point
{

    /**
     * @param INOVERTMessageType $type
     * @param string $id
     * @param NameAndAddress[] $namesAndAddresses
     * @param Date[] $dates
     * @param Mesurement[] $mesurements
     * @param Package[] $packages
     * @param string|null $comments
     */
    public function __construct(
        protected INOVERTMessageType $type,
        protected string  $id,
        protected array $namesAndAddresses = [],
        protected array $dates = [],
        protected array $mesurements = [],
        protected array $packages = [],
        protected ?string $comments = null
    )
    { }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param NameAndAddressType|null $type
     * @return array
     */
    public function getNamesAndAddresses(?NameAndAddressType $type = null): array
    {

        if (!is_null($type)) {
            return array_filter($this->namesAndAddresses, fn($nameAndAddress) => $nameAndAddress->getType() === $type);
        }

        return $this->namesAndAddresses;
    }

    /**
     * @param DateEventType|null $type
     * @return array
     */
    public function getDates(?DateEventType $type = null): array
    {

        if (!is_null($type)) {
            return array_filter($this->dates, fn($date) => $date->getEvent() === $type);
        }
        return $this->dates;
    }

    /**
     * @return array
     */
    public function getMesurements(): array
    {
        return $this->mesurements;
    }

    /**
     * @return array
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @return string|null
     */
    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function getType(): INOVERTMessageType
    {
        return $this->type;
    }

    abstract public function setProductClass($productClass): self;
    abstract public function getProductClass();
}
