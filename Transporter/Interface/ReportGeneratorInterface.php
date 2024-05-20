<?php

namespace Transporter\Interface;

use EDI\Generator\Report;
use Transporter\Enum\ReportReason;
use Transporter\Enum\ReportSituation;

interface ReportGeneratorInterface
{
    public function setDocID(string $docID): ReportGeneratorInterface;
    public function setReference(string $reference): ReportGeneratorInterface;
    public function setReceipt(string $receipt): ReportGeneratorInterface;
    public function setComment(?string $comment): ReportGeneratorInterface;
    public function setPods(array $pods): ReportGeneratorInterface;
    public function setSituation(ReportSituation $situation): ReportGeneratorInterface;
    public function setReason(ReportReason $reason): ReportGeneratorInterface;
    public function setDSJ(\DateTime $datetime): ReportGeneratorInterface;
    public function setAppointment(?\DateTime $appointment): ReportGeneratorInterface;
    public function generate(): Report;
}