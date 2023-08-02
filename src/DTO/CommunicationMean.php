<?php

namespace DBShenker\DTO;

use DBShenker\Enum\CommunicationMeanType;

class CommunicationMean
{
    private CommunicationMeanType $type;

    private string $value;

    /**
     * @param CommunicationMeanType $type
     * @param string $value
     */
    public function __construct(CommunicationMeanType $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return CommunicationMeanType
     */
    public function getType(): CommunicationMeanType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

}