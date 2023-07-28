<?php

namespace DBShenker\DTO;

class Task
{
    private NameAndAddress $nameAndAddress;

    private array $dates;

    private array $communicationMeans;

    /**
     * @return NameAndAddress
     */
    public function getNameAndAddress(): NameAndAddress
    {
        return $this->nameAndAddress;
    }

    /**
     * @param NameAndAddress $nameAndAddress
     * @return Task
     */
    public function setNameAndAddress(NameAndAddress $nameAndAddress): Task
    {
        $this->nameAndAddress = $nameAndAddress;
        return $this;
    }

    /**
     * @return array
     */
    public function getDates(): array
    {
        return $this->dates;
    }

    /**
     * @param array $dates
     * @return Task
     */
    public function setDates(array $dates): Task
    {
        $this->dates = $dates;
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
     * @return Task
     */
    public function setCommunicationMeans(array $communicationMeans): Task
    {
        $this->communicationMeans = $communicationMeans;
        return $this;
    }



}