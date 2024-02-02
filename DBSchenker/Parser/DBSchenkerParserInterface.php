<?php

namespace DBSchenker\Parser;

interface DBSchenkerParserInterface
{
    public function parse(array $message): void;

}