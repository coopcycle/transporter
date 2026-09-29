<?php

namespace Transporter\Transporters\DBSchenker\Parser;

use Transporter\Enum\INOVERTMessageType;
use Transporter\Parser\TransporterParser;
use Transporter\Transporters\DBSchenker\DTO\DBSchenkerPoint;
use Transporter\Transporters\DBSchenker\Enum\DBSchenkerProductClass;

class DBSchenkerScontrParser extends TransporterParser
{
    protected function parseTask(array $task): DBSchenkerPoint
    {
        $point = new DBSchenkerPoint(
            type: INOVERTMessageType::SCONTR,
            id: self::getID($task),
            namesAndAddresses: self::getNamesAndAddresses($task['GR8']),
            dates: self::getDates($task['GR8']),
            mesurements: self::getMesurements($task),
            packages: self::getPackages($task),
            comments: self::getComments($task),
            documents: self::getDocuments($task),
            goods: self::getGoods($task)
        );
        $point->setProductClass(self::getProductClass($task));
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
