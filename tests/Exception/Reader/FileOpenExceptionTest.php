<?php
namespace Tests\PHPPNG\PNGChunks\Exception\Reader;

use PHPPNG\PNGChunks\Exception\Reader\FileOpenException;

use PHPUnit\Framework\TestCase;

class FileOpenExceptionTest extends TestCase
{
    /**
     * @testdox Should throw exception
     * @test
     */
    public function cannotBeCreatedFromNotExistingFile()
    {
        $this->expectException(FileOpenException::class);
        throw new FileOpenException('could-not-open-file.jpg');
    }
}
