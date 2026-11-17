<?php

namespace Transporter\Interface;

interface TransporterSync
{
    /**
     * @param array<int,mixed> $options
     * @return array<string,string> Contents, keyed by their path on the remote (given back to flush())
     */
    public function pull(array $options = []): array;

    /**
     * Pulls the files of the failed folder (failedPath), to retry them.
     * @return array<string,string> Contents, keyed by their path on the remote (given back to flush())
     * @throws \Transporter\TransporterException when no failedPath is configured
     */
    public function pullFailed(): array;

    /**
     * Removes the pulled files from the remote, to acknowledge their import.
     * @param array<string,string> $rejected Files that couldn't be imported: reasons, keyed like pull().
     *                                       They're moved to the failed folder (failedPath), or stay
     *                                       where they are without one, to be pulled again.
     */
    public function flush(bool $dry_run = false, array $rejected = []): void;

    /**
     * @param array<int,mixed> $options
     */
    public function push(string $message, array $options = []): void;
}
