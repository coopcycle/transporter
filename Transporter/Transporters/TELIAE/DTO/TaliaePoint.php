<?php

namespace Transporter\Transporters\TELIAE\DTO;

use Transporter\DTO\Point;

class TaliaePoint extends Point
{

    /**
     * @param mixed $productClass
     * @return $this
     */
    public function setProductClass($productClass): TaliaePoint
    {
        return $this;
    }

    /**
     * @return null
     */
    public function getProductClass()
    {
        return null;
    }

}
