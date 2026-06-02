<?php

namespace Transporter\Transporters\TELIAE\Parser;

use Transporter\Enum\INOVERTMessageType;
use Transporter\Parser\TransporterParser;
use Transporter\Transporters\TELIAE\DTO\TaliaePoint;

class TaliaeDisporParser extends TransporterParser
{
    protected function parseTask(array $task): TaliaePoint
    {
        $point = new TaliaePoint(
            type: INOVERTMessageType::DISPOR,
            id: self::getID($task),
            namesAndAddresses: self::getNamesAndAddresses(array_merge($task['GR8'], $this->scontr['GR1'])),
            dates: array_merge(self::getDates($task['GR8']), self::getDates($this->scontr['GR1'])),
            mesurements: self::getMesurements($task),
            packages: self::getPackages($task),
            comments: self::getComments($task)
        );
        return $point;
    }
}
