<?php

namespace Transporter\Transporters\BMV;

use Transporter\Interface\TransporterSync;
use Transporter\TransporterOptions;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;

class BMVSync implements TransporterSync {

    private array $unflushed = [];

    public function __construct(
        private readonly TransporterOptions $options
    ) { }

    /**
     * @param array $options
     * @return array
     * @throws FilesystemException
     */
    public function pull(array $options = []): array
    {
        $fs = $this->options->getInFilesystem();
        $this->unflushed = $fs->getFilesystem()
            ->listContents('/')
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
            ->filter(fn (FileAttributes $attrs) =>
               !str_ends_with($attrs->path(), '.tmp') | $attrs->fileSize() > 0
            )
            ->toArray();

        return array_map(fn ($file) => $fs->getFilesystem()->read($file->path()), $this->unflushed);
    }

    /**
     * @param bool $dry_run
     * @return void
     * @throws FilesystemException
     */
    public function flush(bool $dry_run = false): void
    {
        $fs = $this->options->getInFilesystem();
        if (!$dry_run) {
            array_walk($this->unflushed, fn ($file) => $fs->getFilesystem()->delete($file->path()));
        }
        $this->unflushed = [];
    }


    /**
     * @param string $message
     * @param array $options
     * @return void
     * @throws FilesystemException
     */
    public function push(string $message, array $options = []): void
    {
        $fs = $this->options->getOutFilesystem();
        $path = sprintf("REPORT.%s_%s.edi",
            date('YmdHis'), uniqid()
        );
        $fs->getFilesystem()->write($path, $message);
    }

}
