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
        $this->unflushed = $this->options->getFilesystem()
            ->listContents(sprintf("to_%s", $this->options->getFilemask()))
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
            ->filter(fn (FileAttributes $attrs) =>
               !str_ends_with($attrs->path(), '.tmp') | $attrs->fileSize() > 0
            )
            ->toArray();

        return array_map(fn ($file) => $this->options->getFilesystem()->read($file->path()), $this->unflushed);
    }

    /**
     * @param bool $dry_run
     * @return void
     * @throws FilesystemException
     */
    public function flush(bool $dry_run = false): void
    {
        if (!$dry_run) {
            array_walk($this->unflushed, fn ($file) => $this->options->getFilesystem()->delete($file->path()));
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
        $path = sprintf("from_%s/REPORT.%s_%s",
            $this->options->getFilemask(),
            date('YmdHis'), uniqid()
        );
        $this->options->getFilesystem()->write(sprintf("%s.tmp", $path), $message);
        $this->options->getFilesystem()->move(sprintf("%s.tmp", $path), $path);
    }

}
