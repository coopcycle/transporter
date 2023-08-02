<?php

namespace DBShenker\DTO;

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
        ?string $comments = null
    )
    {
        $this->id = $id;
        $this->namesAndAddresses = $namesAndAddresses;
        $this->dates = $dates;
        $this->mesurements = $mesurements;
        $this->packages = $packages;
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
    public function getNamesAndAddresses(): array
    {
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
     * @return string|null
     */
    public function getComments(): ?string
    {
        return $this->comments;
    }

}