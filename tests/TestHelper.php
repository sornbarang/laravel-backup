<?php

namespace Spatie\Backup\Tests;

use DateTime;
use Illuminate\Filesystem\Filesystem;

class TestHelper
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function initializeTempDirectory()
    {
        $this->initializeDirectory($this->getTempDirectory());
    }

    public function initializeDirectory(string $directory)
    {
        $this->filesystem->deleteDirectory($directory);

        $this->filesystem->makeDirectory($directory);

        $this->addGitignoreTo($directory);
    }

    public function removeTempDirectory()
    {
        return $this->filesystem->deleteDirectory($this->getTempDirectory());
    }

    public function addGitignoreTo(string $directory)
    {
        $fileName = "{$directory}/.gitignore";

        $fileContents = '*'.PHP_EOL.'!.gitignore';

        $this->filesystem->put($fileName, $fileContents);
    }

    public function getStubDirectory(): string
    {
        return __DIR__.'/stubs';
    }

    public function getStubDbDirectory(): string
    {
        return __DIR__.'/stubs-db';
    }

    public function getTempDirectory(): string
    {
        return __DIR__.'/temp';
    }

    public function createTempFileWithAge($fileName, DateTime $date, $contents = ''): string
    {
        $directory = $this->getTempDirectory().'/'.dirname($fileName);

        $this->filesystem->makeDirectory($directory, 0755, true, true);

        $fullPath = $this->getTempDirectory().'/'.$fileName;

        file_put_contents($fullPath, $contents);

        touch($fullPath, $date->getTimestamp());

        return $fullPath;
    }

    public function createTempFile1Mb(string $fileName, DateTime $date): string
    {
        $directory = $this->getTempDirectory().'/'.dirname($fileName);

        $this->filesystem->makeDirectory($directory, 0755, true, true);

        $sourceFile = $this->getStubDirectory().'/1Mb.file';

        $fullPath = $this->getTempDirectory().'/'.$fileName;

        copy($sourceFile, $fullPath);

        touch($fullPath, $date->getTimestamp());

        return $fullPath;
    }


}
