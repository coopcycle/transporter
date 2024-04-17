<?php

namespace Transporter\DTO;

use Transporter\Enum\ProductType;

class Package
{
    private ProductType $type;

    private int $quantity;

    /**
     * @param ProductType $type
     * @param int $quantity
     */
    public function __construct(ProductType $type, int $quantity)
    {
        $this->type = $type;
        $this->quantity = $quantity;
    }

    /**
     * @return ProductType
     */
    public function getType(): ProductType
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

}