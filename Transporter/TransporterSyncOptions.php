<?php

namespace Transporter;

use League\Flysystem\Filesystem;

/**
 * @property-read ?string $filemask
 * @property-read mixed   $any other free-form attribute set on the instance
 */
class TransporterSyncOptions
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly array      $attributes = [],
        private readonly ?string    $pushPath   = null,
        private readonly ?string    $pullPath   = null,
    ) { }

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

    /**
     * @return ?string
     */
    public function getPushPath(): ?string
    {
        return $this->pushPath;
    }

    /**
     * @return ?string
     */
    public function getPullPath(): ?string
    {
        return $this->pullPath;
    }

    public function __get(string $name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }
}
