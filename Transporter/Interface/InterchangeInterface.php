<?php

namespace Transporter\Interface;

interface InterchangeInterface
{
    public function addGenerator(ReportGeneratorInterface $generator): self;
    public function generate(): string;
}