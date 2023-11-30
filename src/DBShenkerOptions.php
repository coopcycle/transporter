<?php

namespace DBShenker;

use League\Flysystem\Filesystem;

class DBShenkerOptions {

    public function __construct(
        private string $coop_name,
        private string $coop_siret,
        private string $agency_name,
        private string $agency_siret,
        private Filesystem $filesystem,
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
}
