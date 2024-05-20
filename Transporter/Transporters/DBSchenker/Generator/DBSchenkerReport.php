<?php

namespace Transporter\Transporters\DBSchenker\Generator;

use Transporter\TransporterOptions;
use EDI\Generator\Report;
use EDI\Generator\Segment\NameAndAddress;
use Transporter\Enum\ReportReason;
use Transporter\Enum\ReportSituation;
use Transporter\Interface\GeneratorInterface;

class DBSchenkerReport implements GeneratorInterface
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

    public function setDocID(string $docID): DBSchenkerReport
    {
        $this->docID = $docID;
        return $this;
    }
    
    public function setReference(string $reference): DBSchenkerReport
    {
        $this->reference = $reference;
        return $this;
    }

    public function setReceipt(string $receipt): DBSchenkerReport
    {
        $this->receipt = $receipt;
        return $this;
    }

    public function setComment(?string $comment): DBSchenkerReport
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Should be an array of URI publicly accessible of the POD(s)
     * @param array $pods
     * @return $this
     */
    public function setPods(array $pods): DBSchenkerReport
    {
        $this->pods = $pods;
        return $this;
    }

    public function setSituation(ReportSituation $situation): DBSchenkerReport
    {
        $this->situation = $situation;
        return $this;
    }

    public function setReason(ReportReason $reason): DBSchenkerReport
    {
        $this->reason = $reason;
        return $this;
    }

    public function setDSJ(\DateTime $datetime): DBSchenkerReport
    {
        $this->dsj = $datetime;
        return $this;
    }

    public function setAppointment(?\DateTime $appointment): DBSchenkerReport
    {
        $this->appointment = $appointment;
        return $this;
    }


    private function getAgencyNAD(): NameAndAddress
    {
        return (new NameAndAddress())
            ->setPartyFunctionCodeQualifier('MS')
            ->setPartyIdentificationDetails($this->options->getAgencySiret(), '5')
            ->setPartyName([$this->options->getAgencyName()]);
    }

    private function getCoopNAD(): NameAndAddress
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
