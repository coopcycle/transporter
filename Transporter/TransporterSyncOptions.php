<?php

namespace Transporter;

use League\Flysystem\Filesystem;

/**
 * @property-read ?string $filemask
 */
class TransporterSyncOptions
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly array $attributes = []
    ) {}

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function __get(string $name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }
}