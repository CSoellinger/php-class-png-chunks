<?php
namespace Tests\PHPPNG\PNGChunks\Exception;

use PHPPNG\PNGChunks\Exception\InvalidPngException;

use PHPUnit\Framework\TestCase;

class InvalidPngExceptionTest extends TestCase
{
    /**
     * @testdox Should throw exception
     * @test
     */
    public function cannotBeCreatedFromNotExistingFile()
    {
        $this->expectException(InvalidPngException::class);
        throw new InvalidPngException('filepath.png');
    }
}
