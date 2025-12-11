<?php

namespace Transporter;

use League\Flysystem\Filesystem;
use Transporter\Enum\TransporterName;

class TransporterOptions {

    public function __construct(
        private readonly TransporterName        $transporter,
        private readonly string                 $coop_name,
        private readonly string                 $coop_siret,
        private readonly string                 $agency_name,
        private readonly string                 $agency_siret,
        private readonly TransporterSyncOptions $in_filesystem,
        private readonly TransporterSyncOptions $out_filesystem
    ) { }

    /**
     * @return TransporterName
     */
    public function getTransporter(): TransporterName
    {
        return $this->transporter;
    }

    /**
     * @return string
     */
    public function getCoopName(): string
    {
        return $this->coop_name;
    }

    /**
     * @return string
     */
    public function getCoopSiret(): string
    {
        return $this->coop_siret;
    }

    /**
     * @return string
     */
    public function getAgencyName(): string
    {
        return $this->agency_name;
    }

    /**
     * @return string
     */
    public function getAgencySiret(): string
    {
        return $this->agency_siret;
    }

    /**
     * @return TransporterSyncOptions
     */
    public function getOutFilesystem(): TransporterSyncOptions
    {
        return $this->out_filesystem;
    }

    /**
     * @return TransporterSyncOptions
     */
    public function getInFilesystem(): TransporterSyncOptions
    {
        return $this->in_filesystem;
    }
}
