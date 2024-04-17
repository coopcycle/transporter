<?php

namespace Transporter\Interface;

interface TransporterParserInterface
{
    public function parse(array $message): void;

}