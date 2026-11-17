<?php

namespace Transporter\Transporters\BMV;

use Transporter\Interface\TransporterSync;
use Transporter\TransporterException;
use Transporter\TransporterOptions;
use Transporter\Utils\FailedFolder;
use Transporter\Utils\PathTemplate;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;

class BMVSync implements TransporterSync {

    private const DEFAULT_PUSH_PATH = 'REPORT.{{date(\'YmdHis\')}}_{{uniqid()}}.edi';
    private const DEFAULT_PULL_PATH = '/';

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

        return $this->pullFrom(PathTemplate::resolve($tpl, $fs));
    }

    /**
     * @return array<string,string>
     * @throws FilesystemException
     * @throws TransporterException
     */
    public function pullFailed(): array
    {
        return $this->pullFrom(FailedFolder::root($this->options->getInFilesystem()));
    }

    /**
     * @return array<string,string>
     * @throws FilesystemException
     */
    private function pullFrom(string $root): array
    {
        $fs = $this->options->getInFilesystem();

        $files = $fs->getFilesystem()
            ->listContents(empty($root) ? '/' : $root)
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
            ->filter(fn (FileAttributes $attrs) =>
               !str_ends_with($attrs->path(), '.tmp') || $attrs->fileSize() > 0
            )
            ->toArray();

        // Keyed by path, so flush() knows which ones were rejected
        $this->unflushed = [];
        foreach ($files as $file) {
            $this->unflushed[$file->path()] = $file;
        }

        return array_map(fn (FileAttributes $file) => $fs->getFilesystem()->read($file->path()), $this->unflushed);
    }

    /**
     * @param bool $dry_run
     * @param array<string,string> $rejected
     * @return void
     * @throws FilesystemException
     * @throws TransporterException
     */
    public function flush(bool $dry_run = false, array $rejected = []): void
    {
        $fs = $this->options->getInFilesystem();
        if (!$dry_run) {
            $acknowledged = array_diff_key($this->unflushed, $rejected);
            array_walk($acknowledged, fn (FileAttributes $file) => $fs->getFilesystem()->delete($file->path()));
            FailedFolder::moveTo(array_intersect_key($this->unflushed, $rejected), $fs);
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
