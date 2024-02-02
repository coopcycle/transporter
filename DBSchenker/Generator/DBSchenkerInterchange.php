<?php

namespace DBSchenker\Generator;

use DBSchenker\DBSchenkerOptions;
use EDI\Encoder;
use EDI\Generator\Interchange;

class DBSchenkerInterchange
{

    /**
     * @var array<DBSchenkerGeneratorInterface>
     */
    private array $generators = [];

    public function __construct(
        private readonly DBSchenkerOptions $options
    )
    { }

    public function addGenerator(DBSchenkerGeneratorInterface $generator): self
    {
        $this->generators[] = $generator;
        return $this;
    }

    public function generate(): string
    {
        $interchange = (new Interchange(
            [$this->options->getCoopName(), '22'],
            [$this->options->getAgencyName(), '22'],
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