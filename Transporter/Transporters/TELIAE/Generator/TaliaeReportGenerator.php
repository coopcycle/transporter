<?php

namespace Transporter\Transporters\TELIAE\Generator;

use EDI\Generator\Segment\NameAndAddress;
use Transporter\Generator\EDIFACTReportGenerator;

class TaliaeReportGenerator extends EDIFACTReportGenerator
{

    #[\Override]
    protected function getAgencyNAD(): NameAndAddress
    {
        return (new NameAndAddress())
            ->setPartyFunctionCodeQualifier('MR')
            ->setPartyIdentificationDetails($this->options->getAgencySiret(), '5')
            ->setPartyName([$this->options->getAgencyName()]);
    }


    #[\Override]
    protected function getCoopNAD(): NameAndAddress
    {
        return (new NameAndAddress())
            ->setPartyFunctionCodeQualifier('MS')
            ->setPartyIdentificationDetails($this->options->getCoopSiret(), '5')
            ->setPartyName([$this->options->getCoopName()]);
    }

}
