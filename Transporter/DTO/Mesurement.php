<?php

namespace Transporter\DTO;

use Transporter\Enum\QuantityType;
use Transporter\Enum\QuantityUnitType;

final class Mesurement
{

    private QuantityType $type;

    private float $quantity;

    private QuantityUnitType $unit;

    /**
     * @param QuantityType $type
     * @param float $quantity
     * @param QuantityUnitType $unit
     */
    public function __construct(QuantityType $type, float $quantity, QuantityUnitType $unit)
    {
        $this->type = $type;
        $this->quantity = $quantity;
        $this->unit = $unit;
    }

    /**
     * @return QuantityType
     */
    public function getType(): QuantityType
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @return QuantityUnitType
     */
    public function getUnit(): QuantityUnitType
    {
        return $this->unit;
    }

}
