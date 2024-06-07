<?php

namespace Transporter\Generator;

use Transporter\TransporterOptions;
use EDI\Generator\Report;
use EDI\Generator\Segment\NameAndAddress;
use Transporter\Enum\ReportReason;
use Transporter\Enum\ReportSituation;
use Transporter\Interface\ReportGeneratorInterface;

class EDIFACTReportGenerator implements ReportGeneratorInterface
{
    private string $docID;
    private string $reference;
    private string $receipt;
    private ?string $comment = null;
    private array $pods = [];
    private ReportSituation $situation;
    private ReportReason $reason;
    private ?\DateTime $dsj = null;
    private ?\DateTime $appointment = null;

    public function __construct(
        private readonly TransporterOptions $options
    ) { }

    public function setDocID(string $docID): ReportGeneratorInterface
    {
        $this->docID = $docID;
        return $this;
    }
    
    public function setReference(string $reference): ReportGeneratorInterface
    {
        $this->reference = $reference;
        return $this;
    }

    public function setReceipt(string $receipt): ReportGeneratorInterface
    {
        $this->receipt = $receipt;
        return $this;
    }

    public function setComment(?string $comment): ReportGeneratorInterface
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Should be an array of URI publicly accessible of the POD(s)
     * @param array $pods
     * @return $this
     */
    public function setPods(array $pods): ReportGeneratorInterface
    {
        $this->pods = $pods;
        return $this;
    }

    public function setSituation(ReportSituation $situation): ReportGeneratorInterface
    {
        $this->situation = $situation;
        return $this;
    }

    public function setReason(ReportReason $reason): ReportGeneratorInterface
    {
        $this->reason = $reason;
        return $this;
    }

    public function setDSJ(\DateTime $datetime): ReportGeneratorInterface
    {
        $this->dsj = $datetime;
        return $this;
    }

    public function setAppointment(?\DateTime $appointment): ReportGeneratorInterface
    {
        $this->appointment = $appointment;
        return $this;
    }


    protected  function getAgencyNAD(): NameAndAddress
    {
        return (new NameAndAddress())
            ->setPartyFunctionCodeQualifier('MS')
            ->setPartyIdentificationDetails($this->options->getAgencySiret(), '5')
            ->setPartyName([$this->options->getAgencyName()]);
    }

    protected function getCoopNAD(): NameAndAddress
    {
        return (new NameAndAddress())
            ->setPartyFunctionCodeQualifier('MR')
            ->setPartyIdentificationDetails($this->options->getCoopSiret(), '5')
            ->setPartyName([$this->options->getCoopName()]);
    }


    public function generate(): Report
    {

        $report = (new Report($this->docID))
            ->addNAD($this->getCoopNAD())
            ->addNAD($this->getAgencyNAD())
            ->setReference($this->reference)
            ->setReason($this->situation->name, $this->reason->name)
            ->setReceipt($this->receipt)
            ->setComment($this->comment)
            ->setPOD($this->pods);

        if (!is_null($this->dsj)) {
            $report->setDTM($this->dsj, 'DSJ');
        }
        if (!is_null($this->appointment)) {
            $report->setDTM($this->appointment, 'DAD');
        }

        return $report;
    }
}
