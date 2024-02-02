<?php

namespace DBSchenker\DTO;

use DBSchenker\Enum\NameAndAddressType;
use DBSchenker\Enum\ProductClass;

class GR7
{

    private string $id;

    /** @var array<NameAndAddress>  */
    private array $namesAndAddresses;

    /** @var array<Date>  */
    private array $dates;

    /** @var array<Mesurement> */
    private array $mesurements;

    /** @var array<Package> */
    private array $packages;

    private ProductClass $productClass;

    private ?string $comments;

    /**
     * @param string $id
     * @param NameAndAddress[] $namesAndAddresses
     * @param Date[] $dates
     * @param Mesurement[] $mesurements
     * @param Package[] $packages
     * @param string|null $comments
     */
    public function __construct(
        string $id,
        array $namesAndAddresses = [],
        array $dates = [],
        array $mesurements = [],
        array $packages = [],
        ProductClass $productClass,
        ?string $comments = null
    )
    {
        $this->id = $id;
        $this->namesAndAddresses = $namesAndAddresses;
        $this->dates = $dates;
        $this->mesurements = $mesurements;
        $this->packages = $packages;
        $this->productClass = $productClass;
        $this->comments = $comments;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
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
     * @return array
     */
    public function getDates(): array
    {
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
    * @return ProductClass
    */
    public function getProductClass(): ProductClass
    {
        return $this->productClass;
    }

    /**
     * @return string|null
     */
    public function getComments(): ?string
    {
        return $this->comments;
    }

}
