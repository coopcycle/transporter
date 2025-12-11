<?php

namespace Transporter\Transporters\DBSchenker\Generator;

use EDI\Generator\Report;
use Transporter\Generator\EDIFACTReportGenerator;

class DBSchenkerReportGenerator extends EDIFACTReportGenerator
{
    public function generate(): Report
    {

        $report = (new Report($this->docID))
            ->addNAD($this->getCoopNAD())
            ->addNAD($this->getAgencyNAD())
            ->setReference($this->reference)
            ->setReason($this->situation->name, $this->reason->name)
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
