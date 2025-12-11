<?php

namespace Transporter\Transporters\DMV\Parser;

use Transporter\Enum\INOVERTMessageType;
use Transporter\Parser\TransporterParser;
use Transporter\Transporters\DMV\DTO\BMVPoint;
use Transporter\Transporters\DMV\Enum\BMVProductClass;

class BMVScontrParser extends TransporterParser
{
    protected function parseTask(array $task): BMVPoint
    {
        $point = new BMVPoint(
            type: INOVERTMessageType::SCONTR,
            id: self::getID($task),
            namesAndAddresses: self::getNamesAndAddresses($task['GR8']),
            dates: self::getDates($task['GR8']),
            mesurements: self::getMesurements($task),
            packages: self::getPackages($task),
            comments: self::getComments($task)
        );
        $point->setProductClass(self::getProductClass($task));
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
