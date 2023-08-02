<?php

namespace DBShenker\Parser;

interface DBShenkerParserInterface
{
    public function parse(array $message): void;

}