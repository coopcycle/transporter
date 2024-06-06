<?php

namespace Transporter\Transporters\BMV\Parser;

use Transporter\Parser\TransporterParser;
use Transporter\Transporters\BMV\DTO\BMVPoint;
use Transporter\Transporters\BMV\Enum\BMVProductClass;

class BMVScontrParser extends TransporterParser
{
    protected function gr7ed(array $gr7): BMVPoint
    {
        $point = new BMVPoint(
            id: self::getID($gr7),
            namesAndAddresses: self::getNamesAndAddresses($gr7, $this->addressGeocoder),
            dates: self::getDates($gr7),
            mesurements: self::getMesurements($gr7),
            packages: self::getPackages($gr7),
            comments: self::getComments($gr7)
        );
        $point->setProductClass(self::getProductClass($gr7));
        return $point;
    }


    /**
     * @param array $message
     * @return BMVProductClass|null
     */
    private static function getProductClass(array $message): ?BMVProductClass
    {
        if (isset($message['productType'])) {
            return BMVProductClass::from(
                $message['productType']['regime'],
                $message['productType']['productType']
            );
        } else {
            return BMVProductClass::UNKNOWN;
        }
    }

}
