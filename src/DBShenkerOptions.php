<?php

namespace DBShenker;

use League\Flysystem\Filesystem;

class DBShenkerOptions {

    public function __construct(
        private readonly string     $coop_name,
        private readonly string     $coop_siret,
        private readonly string     $agency_name,
        private readonly string     $agency_siret,
        private readonly Filesystem $filesystem,
        private readonly string     $filemask
    ) { }

    public function getCoopName(): string
    {
        return $this->coop_name;
    }

    public function getCoopSiret(): string
    {
        return $this->coop_siret;
    }

    public function getAgencyName(): string
    {
        return $this->agency_name;
    }

    public function getAgencySiret(): string
    {
        return $this->agency_siret;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function getFilemask(): string
    {
        return $this->filemask;
    }
}
