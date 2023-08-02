<?php

namespace DBShenker;

use DBShenker\Enum\INOVERTMessageType;
use DBShenker\Parser\DBShenkerParserInterface;
use DBShenker\Parser\DBShenkerScontrParser;
use EDI\Analyser;
use EDI\Interpreter;
use EDI\Mapping\MappingProvider;
use EDI\Parser;

class DBShenker
{

    /**
     * Parse INOVERT file or string
     *
     * @param string $inovert
     * @param INOVERTMessageType $messageType
     * @return DBShenkerParserInterface
     * @throws DBShenkerException
     */
    public static function parse(string $inovert, INOVERTMessageType $messageType = INOVERTMessageType::SCONTR): DBShenkerParserInterface
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
            throw new DBShenkerException('No messages found');
        }

        if (count($prep) > 1) {
            throw new DBShenkerException('Too many messages, not yet implemented');
        }

        $DBParser = match ($messageType) {
            INOVERTMessageType::SCONTR => new DBShenkerScontrParser(),
            default => throw new DBShenkerException(sprintf("Unsupported message type: %s", $messageType->value)),
        };

        $DBParser->parse($prep[0]);
        return $DBParser;
    }
}