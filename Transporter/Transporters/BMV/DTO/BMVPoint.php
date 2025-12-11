<?php

namespace Transporter\Transporters\BMV\DTO;

use Transporter\DTO\Date;
use Transporter\DTO\Mesurement;
use Transporter\DTO\NameAndAddress;
use Transporter\DTO\Package;
use Transporter\DTO\Point;
use Transporter\Enum\DateEventType;
use Transporter\Enum\NameAndAddressType;
use Transporter\Transporters\DBSchenker\Enum\DBSchenkerProductClass;
use Transporter\Transporters\BMV\Enum\BMVProductClass;

class BMVPoint extends Point
{

    protected ?BMVProductClass $productClass = null;

    /**
     * @param BMVProductClass|null $productClass
     * @return $this
     */
    public function setProductClass($productClass): BMVPoint
    {
        $this->productClass = $productClass;
        return $this;
    }

    /**
     * @return BMVProductClass|null
     */
    public function getProductClass(): ?BMVProductClass
    {
        return $this->productClass;
    }

}
