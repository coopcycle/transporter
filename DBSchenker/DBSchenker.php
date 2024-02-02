<?php

namespace DBSchenker;

use DBSchenker\Enum\INOVERTMessageType;
use DBSchenker\Parser\DBSchenkerParserInterface;
use DBSchenker\Parser\DBSchenkerScontrParser;
use EDI\Analyser;
use EDI\Interpreter;
use EDI\Mapping\MappingProvider;
use EDI\Parser;

class DBSchenker
{

    /**
     * Parse INOVERT file or string
     *
     * @param string $inovert
     * @param INOVERTMessageType $messageType
     * @return DBSchenkerParserInterface[]
     * @throws DBSchenkerException
     */
    public static function parse(string $inovert, INOVERTMessageType $messageType = INOVERTMessageType::SCONTR): array
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
            throw new DBSchenkerException('No messages found');
        }

        return array_reduce($prep, function ($acc, $v) use ($messageType) {
            $DBParser = match ($messageType) {
                INOVERTMessageType::SCONTR => new DBSchenkerScontrParser(),
                default => throw new DBSchenkerException(sprintf("Unsupported message type: %s", $messageType->value)),
            };
            $DBParser->parse($v);
            $acc[] = $DBParser;
            return $acc;
        }, []);
    }
}