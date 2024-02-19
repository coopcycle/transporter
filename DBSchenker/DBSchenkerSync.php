<?php

namespace DBSchenker;

use League\Flysystem\FilesystemException;

class DBSchenkerSync {

    private array $unflushed = [];

    public function __construct(
        private readonly DBSchenkerOptions $options
    ) { }

    /**
     * @return array
     * @throws FilesystemException
     */
    public function pull(): array
    {
        $this->unflushed = array_filter(
            $this->options->getFilesystem()
            ->listContents(sprintf("from_%s", $this->options->getFilemask())),
            fn ($file) => !str_ends_with($file['path'], '.tmp') & $file['size'] > 0 & $file['type'] === 'file'
        );

        return array_map(fn ($file) => $this->options->getFilesystem()->read($file['path']), $this->unflushed);
    }

    /**
     * @param bool $dry_run
     * @return void
     * @throws FilesystemException
     */
    public function flush(bool $dry_run = false): void
    {
        if ($dry_run) {
            array_walk($this->unflushed, fn ($file) => $this->options->getFilesystem()->delete($file->path()));
        }
        $this->unflushed = [];
    }


    /**
     * @param string $message
     * @return void
     * @throws FilesystemException
     */
    public function push(string $message): void
    {
        $path = sprintf("to_%s/%s.%s_%s",
            $this->options->getFilemask(),
            $this->options->getFilemask(),
            date('YmdHis'), uniqid()
        );
        $this->options->getFilesystem()->write(sprintf("%s.tmp", $path), $message);
        $this->options->getFilesystem()->rename(sprintf("%s.tmp", $path), $path);
    }

}