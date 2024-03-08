<?php

namespace R64\NovaFields\Http\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

abstract class AbstractNamingStrategy
{
    /**
     * @var Filesystem
     */
    protected $storage;

    public function __construct(Filesystem $storage)
    {
        $this->storage = $storage;
    }

    abstract public function name(string $currentFolder, UploadedFile $file): string;
}
