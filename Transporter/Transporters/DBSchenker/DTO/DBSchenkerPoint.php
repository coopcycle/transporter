<?php

namespace Transporter\Transporters\DBSchenker\DTO;

use Transporter\DTO\Date;
use Transporter\DTO\Mesurement;
use Transporter\DTO\NameAndAddress;
use Transporter\DTO\Package;
use Transporter\DTO\Point;
use Transporter\Enum\DateEventType;
use Transporter\Enum\NameAndAddressType;
use Transporter\Transporters\DBSchenker\Enum\DBSchenkerProductClass;

class DBSchenkerPoint extends Point
{

    protected ?DBSchenkerProductClass $productClass = null;

    /**
     * @param DBSchenkerProductClass|null $productClass
     * @return $this
     */
    public function setProductClass($productClass): DBSchenkerPoint
    {
        $this->productClass = $productClass;
        return $this;
    }

    /**
     * @return DBSchenkerProductClass|null
     */
    public function getProductClass(): ?DBSchenkerProductClass
    {
        return $this->productClass;
    }

}
