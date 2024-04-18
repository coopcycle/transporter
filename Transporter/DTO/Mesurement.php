<?php

namespace Transporter\DTO;

use Transporter\Enum\QuantityType;
use Transporter\Enum\QuantityUnitType;

final class Mesurement
{

    private QuantityType $type;

    private int $quantity;

    private QuantityUnitType $unit;

    /**
     * @param QuantityType $type
     * @param int $quantity
     * @param QuantityUnitType $unit
     */
    public function __construct(QuantityType $type, int $quantity, QuantityUnitType $unit)
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
     * @return int
     */
    public function getQuantity(): int
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
