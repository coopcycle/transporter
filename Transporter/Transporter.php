<?php

namespace Transporter;

use EDI\Analyser;
use EDI\Interpreter;
use EDI\Mapping\MappingProvider;
use EDI\Parser;
use Transporter\Enum\INOVERTMessageType;
use Transporter\Enum\TransporterName;
use Transporter\Interface\TransporterParserInterface;
use Transporter\Transporters\DBSchenker\Parser\DBSchenkerPickupParser;
use Transporter\Transporters\DBSchenker\Parser\DBSchenkerScontrParser;
use Transporter\Transporters\BMV\Parser\BMVScontrParser;

class Transporter
{

    /**
     * Parse INOVERT file or string
     *
     * @param string $inovert
     * @param INOVERTMessageType $messageType
     * @param TransporterName $transporter
     * @return TransporterParserInterface[]
     * @throws TransporterException
     */
    public static function parse(
        string $inovert,
        INOVERTMessageType $messageType = INOVERTMessageType::SCONTR,
        TransporterName $transporter = TransporterName::DBSCHENKER
    ): array
    {
        $parser = new Parser($inovert);
        $parsed = $parser->get();

        $mapping = new MappingProvider('INOVERT');

        $analyser = new Analyser();
        $segmentsXml = $analyser->loadSegmentsXml($mapping->getSegments());
        $svc = $analyser->loadSegmentsXml($mapping->getServiceSegments());

        $interpreter = new Interpreter(
            $mapping->getMessage($messageType->value),
            $segmentsXml,
            $svc
        );
        $prep = $interpreter->prepare($parsed);

        if (count($prep) == 0) {
            throw new TransporterException('No messages found');
        }

        return array_reduce($prep, function ($acc, $v) use ($transporter, $messageType) {
            $implParser = match ([$transporter, $messageType]) {
                [TransporterName::DBSCHENKER, INOVERTMessageType::SCONTR] => new DBSchenkerScontrParser(),
                [TransporterName::BMV, INOVERTMessageType::SCONTR] => new BMVScontrParser(),
        [TransporterName::DB_SCHENKER, INOVERTMessageType::PICKUP] => new DBSchenkerPickupParser(),
                default => throw new TransporterException(sprintf("Unsupported message type: %s for transporter: %s", $messageType->value, $transporter->value)),
            };
            $implParser->parse($v);
            $acc[] = $implParser;
            return $acc;
        }, []);
    }
}
