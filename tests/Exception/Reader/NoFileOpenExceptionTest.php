<?php
namespace Tests\PHPPNG\PNGChunks\Exception\Reader;

use PHPPNG\PNGChunks\Exception\Reader\NoFileOpenException;

use PHPUnit\Framework\TestCase;

class NoFileOpenExceptionTest extends TestCase
{
    /**
     * @testdox Should throw exception
     * @test
     */
    public function cannotBeCreatedFromNotExistingFile()
    {
        $this->expectException(NoFileOpenException::class);
        throw new NoFileOpenException;
    }
}
