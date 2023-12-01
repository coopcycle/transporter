<?php

namespace DBShenker\Generator;

use DBShenker\DBShenkerOptions;
use DBShenker\Enum\ReportReason;
use DBShenker\Enum\ReportSituation;
use EDI\Generator\Report;
use EDI\Generator\Segment\NameAndAddress;

class DBShenkerReport implements DBShenkerGeneratorInterface
{
    private string $reference;
    private string $receipt;
    private ?string $comment = null;
    private array $pod = [];
    private ReportSituation $situation;
    private ReportReason $reason;
    private ?\DateTime $appointment = null;

    public function __construct(
        private readonly DBShenkerOptions $options
    ) { }

    public function setReference(string $reference): DBShenkerReport
    {
        $this->reference = $reference;
        return $this;
    }

    public function setReceipt(string $receipt): DBShenkerReport
    {
        $this->receipt = $receipt;
        return $this;
    }

    public function setComment(?string $comment): DBShenkerReport
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Should be an array of URI publicly accessible of the POD(s)
     * @param array $pod
     * @return $this
     */
    public function setPod(array $pod): DBShenkerReport
    {
        $this->pod = $pod;
        return $this;
    }

    public function setSituation(ReportSituation $situation): DBShenkerReport
    {
        $this->situation = $situation;
        return $this;
    }

    public function setReason(ReportReason $reason): DBShenkerReport
    {
        $this->reason = $reason;
        return $this;
    }

    public function setAppointment(?\DateTime $appointment): DBShenkerReport
    {
        $this->appointment = $appointment;
        return $this;
    }


    private function getAgencyNAD(): NameAndAddress
    {
        return (new NameAndAddress())
            ->setPartyFunctionCodeQualifier('MS')
            ->setPartyIdentificationDetails($this->options->getAgencySiret(), 5)
            ->setPartyName([$this->options->getAgencyName()]);
    }

    private function getCoopNAD(): NameAndAddress
    {
        return (new NameAndAddress())
            ->setPartyFunctionCodeQualifier('MR')
            ->setPartyIdentificationDetails($this->options->getCoopSiret(), 5)
            ->setPartyName([$this->options->getCoopName()]);
    }


    public function generate(): Report
    {
        $report = (new Report())
            ->addNAD($this->getCoopNAD())
            ->addNAD($this->getAgencyNAD())
            ->setReference($this->reference)
            ->setReason($this->situation->name, $this->reason->name)
            ->setReceipt($this->receipt)
            ->setComment($this->comment)
            ->setPOD($this->pod);

        if (!is_null($this->appointment)) {
            $report->setDTM($this->appointment, 'DAD');
        }

        return $report;
    }



}