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
     * @param TransporterName $transporter
     * @param ?INOVERTMessageType $messageType
     * @return [INOVERTMessageType, TransporterParserInterface[]]
     * @throws TransporterException
     */
    public static function parse(
        string $inovert,
        TransporterName $transporter,
        ?INOVERTMessageType $messageType = null,
    ): array
    {

        if (is_null($messageType)) {
            $messageType = self::tryGuessMessageType($inovert);
        }

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

        $tasks = array_reduce($prep, function ($acc, $v) use ($transporter, $messageType) {
            $implParser = match ([$transporter, $messageType]) {
                [TransporterName::DBSCHENKER, INOVERTMessageType::SCONTR] => new DBSchenkerScontrParser(),
                [TransporterName::BMV, INOVERTMessageType::SCONTR] => new BMVScontrParser(),
                [TransporterName::DBSCHENKER, INOVERTMessageType::PICKUP] => new DBSchenkerPickupParser(),
                default => throw new TransporterException(sprintf("Unsupported message type: %s for transporter: %s", $messageType->value, $transporter->value)),
            };
            $implParser->parse($v);
            $acc[] = $implParser;
            return $acc;
        }, []);

        return [$messageType, $tasks];
    }

    private static function tryGuessMessageType(string $inovert): INOVERTMessageType
    {
        preg_match("/UNH\+.+?(?P<type>PICKUP|SCONTR).+?'/m", $inovert, $matches);
        try {
            return INOVERTMessageType::from(strtolower($matches['type']));
        } catch (\Exception $e) {
            throw new TransporterException('Unable to guess message type');
        }
    }
}
