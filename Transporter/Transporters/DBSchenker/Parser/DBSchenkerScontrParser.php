<?php

namespace Transporter\Transporters\DBSchenker\Parser;

use Transporter\Parser\TransporterParser;
use Transporter\Transporters\DBSchenker\DTO\DBSchenkerPoint;
use Transporter\Transporters\DBSchenker\Enum\DBSchenkerProductClass;

class DBSchenkerScontrParser extends TransporterParser
{
    protected function gr7ed(array $gr7): DBSchenkerPoint
    {
        $point = new DBSchenkerPoint(
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
     * @return DBSchenkerProductClass
     */
    private static function getProductClass(array $message): DBSchenkerProductClass
    {
        if (isset($message['productType'])) {
            return DBSchenkerProductClass::from(
                $message['productType']['regime'],
                $message['productType']['productType']
            );
        } else {
            return DBSchenkerProductClass::UNKNOWN;
        }
    }
}
