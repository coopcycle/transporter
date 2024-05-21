<?php

namespace Transporter\Interface;

interface TransporterSync
{
    /**
     * @param array<int,mixed> $options
     * @return array<string>
     */
    public function pull(array $options = []): array;
    public function flush(bool $dry_run = false): void;
    /**
     * @param array<int,mixed> $options
     */
    public function push(string $message, array $options = []): void;
}
