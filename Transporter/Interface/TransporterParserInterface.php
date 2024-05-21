<?php

namespace Transporter\Interface;

use Transporter\DTO\Point;

interface TransporterParserInterface
{
    /**
     * @param array<string> $message
     */
    public function parse(array $message): void;

    /**
     * @return array<Point>
     */
    public function getTasks(): array;

}
