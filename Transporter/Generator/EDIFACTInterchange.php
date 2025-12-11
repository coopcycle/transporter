<?php

namespace Transporter\Generator;

use Transporter\Interface\InterchangeInterface;
use Transporter\TransporterOptions;
use EDI\Encoder;
use EDI\Generator\Interchange;
use Transporter\Interface\ReportGeneratorInterface;

class EDIFACTInterchange implements InterchangeInterface
{

    /**
     * @var array<ReportGeneratorInterface>
     */
    private array $generators = [];

    public function __construct(
        private readonly TransporterOptions $options
    )
    { }

    public function addGenerator(ReportGeneratorInterface $generator): self
    {
        $this->generators[] = $generator;
        return $this;
    }

    public function generate(): string
    {
        $interchange = (new Interchange(
            [$this->options->getCoopSiret(), '22'],
            [$this->options->getAgencySiret(), '22'],
        ))->setCharset('UNOC', '1');

        foreach ($this->generators as $generator) {
            $interchange->addMessage($generator->generate()->compose());
        }

        $encoder = new Encoder(
            $interchange->getComposed(),
            false
        );
        $encoder->enableUNA();
        return $encoder->get();

    }

}
