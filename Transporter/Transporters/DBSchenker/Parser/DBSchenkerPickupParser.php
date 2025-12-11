<?php

namespace Transporter\Transporters\DBSchenker\Parser;

use Transporter\Enum\INOVERTMessageType;
use Transporter\Parser\TransporterParser;
use Transporter\Transporters\DBSchenker\DTO\DBSchenkerPoint;
use Transporter\Transporters\DBSchenker\Enum\DBSchenkerProductClass;

class DBSchenkerPickupParser extends TransporterParser
{
    protected function parseTask(array $task): DBSchenkerPoint
    {
        $point = new DBSchenkerPoint(
            type: INOVERTMessageType::PICKUP,
            id: self::getID($task),
            namesAndAddresses: self::getNamesAndAddresses($task['GR8']),
            dates: self::getDates($task['GR8']),
            mesurements: self::getMesurements($task),
            packages: self::getPackages($task),
            comments: self::getComments($task)
        );
        $point->setProductClass(DBSchenkerProductClass::UNKNOWN);
        return $point;
    }
}
