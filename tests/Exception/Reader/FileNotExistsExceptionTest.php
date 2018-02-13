<?php
namespace Tests\PHPPNG\PNGChunks\Exception\Reader;

use PHPPNG\PNGChunks\Exception\Reader\FileNotExistsException;

use PHPUnit\Framework\TestCase;

class FileNotExistsExceptionTest extends TestCase
{
    /**
     * @testdox Should throw exception
     * @test
     */
    public function cannotBeCreatedFromNotExistingFile()
    {
        $this->expectException(FileNotExistsException::class);
        throw new FileNotExistsException('file-not-exists.jpg');
    }
}
