<?php

namespace DBShenker;

use League\Flysystem\Filesystem;

class DBShenkerSync {

    private array $unflushed = [];

    public function __construct(
        private Filesystem $filesystem,
        private string $from_directoy,
        private string $to_directory
    ) { }

    /**
     * @return string[]
     */
    public function pull(): array
    {
        $this->unflushed = $this->filesystem->listContents($this->from_directoy)
            ->filter(fn ($file) => !str_ends_with($file->path(), '.tmp') |
                    $file->isFile() |
                    $file->fileSize() > 0)
            ->toArray();

        return array_map(fn ($file) => $this->filesystem->read($file->path()), $this->unflushed);
    } 

    public function flush(): void
    {
        array_walk($this->unflushed, fn ($file) => $this->filesystem->delete($file->path()));
        $this->unflushed = [];
    }

}
