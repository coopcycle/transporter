<?php

namespace Transporter\Transporters\DBSchenker;

use Transporter\Interface\TransporterSync;
use Transporter\TransporterOptions;
use Transporter\Utils\PathTemplate;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;

class DBSchenkerSync implements TransporterSync {

    private const DEFAULT_PUSH_PATH = 'from_{filemask}/{filemask}.{{date(\'YmdHis\')}}_{{uniqid()}}';
    private const DEFAULT_PULL_PATH = 'to_{filemask}';

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
        $fs   = $this->options->getInFilesystem();
        $tpl  = $fs->getPullPath() ?? self::DEFAULT_PULL_PATH;
        $root = PathTemplate::resolve($tpl, $fs);

        $this->unflushed = $fs->getFilesystem()
            ->listContents(empty($root) ? '/' : $root)
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
            ->filter(fn (FileAttributes $attrs) =>
               !str_ends_with($attrs->path(), '.tmp') || $attrs->fileSize() > 0
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
        $fs   = $this->options->getOutFilesystem();
        $tpl  = $fs->getPushPath() ?? self::DEFAULT_PUSH_PATH;
        $path = PathTemplate::resolve($tpl, $fs);

        $fs->getFilesystem()->write(sprintf('%s.tmp', $path), $message);
        $fs->getFilesystem()->move(sprintf('%s.tmp', $path), $path);
    }

}
