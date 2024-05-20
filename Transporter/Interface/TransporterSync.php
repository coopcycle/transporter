<?php

namespace Transporter\Interface;

interface TransporterSync
{
    public function pull(array $options = []): array;
    public function flush(bool $dry_run = false): void;
    public function push(string $message, array $options = []): void;
}