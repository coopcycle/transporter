<?php

namespace Transporter;


use Transporter\Enum\TransporterName;
use Transporter\Transporters\DBSchenker\DBSchenkerSync;
use Transporter\Transporters\DBSchenker\Generator\DBSchenkerInterchange;
use Transporter\Transporters\DBSchenker\Generator\DBSchenkerReportGenerator;
use Transporter\Transporters\DMV\BMVSync;

/**
 * Class TransporterImpl
 * @property-read string $sync
 * @property-read string $reportGenerator
 * @property-read string $interchange
 */
class TransporterImpl {

    private string $use;
    private array $implementations = [
        'DBSCHENKER' => [
            'sync' => DBSchenkerSync::class,
            'reportGenerator' => DBSchenkerReportGenerator::class,
            'interchange' => DBSchenkerInterchange::class
        ],
        'BMV' => [
            'sync' => BMVSync::class
        ]
    ];

    public function __construct(
        TransporterName $transporterName
    )
    {
        $this->use = $transporterName->value;
    }

    /**
     * @throws TransporterException
     */
    public function __get(string $name): string
    {
        if (isset($this->implementations[$this->use][$name])) {
            return $this->implementations[$this->use][$name];
        }
        throw new TransporterException(
            sprintf("Transporter %s does not support %s",
                $this->use, $name)
        );
    }
}