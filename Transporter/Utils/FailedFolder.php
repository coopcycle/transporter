<?php

declare(strict_types=1);

namespace Transporter\Utils;

use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemException;
use Transporter\TransporterException;
use Transporter\TransporterSyncOptions;

/**
 * The folder, on the inbound filesystem, where the files that couldn't be
 * imported are set aside (TransporterSyncOptions::$failedPath).
 */
final class FailedFolder
{
    /**
     * @throws TransporterException when no failedPath is configured
     */
    public static function root(TransporterSyncOptions $fs): string
    {
        $tpl = $fs->getFailedPath();
        if (is_null($tpl)) {
            throw new TransporterException('No failedPath configured');
        }

        return trim(PathTemplate::resolve($tpl, $fs), '/');
    }

    /**
     * Moves the rejected files to the failed folder. Without one, they stay
     * where they are, to be pulled again.
     *
     * @param array<FileAttributes> $files
     * @throws FilesystemException
     * @throws TransporterException
     */
    public static function moveTo(array $files, TransporterSyncOptions $fs): void
    {
        if (empty($files) || is_null($fs->getFailedPath())) {
            return;
        }

        $root = self::root($fs);
        foreach ($files as $file) {
            // Pulled from the failed folder (a retry): it's already there
            if (dirname($file->path()) === $root) {
                continue;
            }

            $target = sprintf('%s/%s', $root, basename($file->path()));
            // A file with the same name was already rejected: keep both
            if ($fs->getFilesystem()->fileExists($target)) {
                $target = sprintf('%s/%s_%s', $root, uniqid(), basename($file->path()));
            }

            $fs->getFilesystem()->move($file->path(), $target);
        }
    }
}
