<?php
namespace Tests\PHPPNG\PNGChunks;

use PHPUnit\Framework\TestCase;

use PHPPNG\PNGChunks\PNGChunks;
use PHPPNG\PNGChunks\Exception\InvalidPngException;
use PHPPNG\PNGChunks\Exception\Reader\FileNotExistsException;

class PNGChunksTest extends TestCase
{

    public static $pngOrig = __DIR__ . '/../assets/test.png';
    public static $invalidPng = __DIR__ . '/../assets/jpeg-as.png';
    public static $png = __DIR__ . '/../assets/test_copy.png';

    /**
     * @before
     */
    public static function copyTestPng()
    {
        if (file_exists(PNGChunksTest::$png)) {
            unlink(PNGChunksTest::$png);
        }
        copy(PNGChunksTest::$pngOrig, PNGChunksTest::$png);
    }

    /**
     * @after
     */
    public static function removeTestPng()
    {
        unlink(PNGChunksTest::$png);
    }

    /**
     * @testdox Should create an instance with a valid file
     * @test
     */
    public function canBeCreatedFromValidPng()
    {
        $pngFile = PNGChunksTest::$png;
        $this->assertInstanceOf(
            PNGChunks::class,
            new PNGChunks($pngFile)
        );
    }

    /**
     * @testdox Should throw exception when giving a non existing file
     * @test
     */
    public function cannotBeCreatedFromNotExistingFile()
    {
        $this->expectException(FileNotExistsException::class);
        new PNGChunks('no-file.png');
    }

    /**
     * @testdox Should throw exception when giving a wrong PNG
     * @test
     */
    public function cannotBeCreatedFromInvalidPng()
    {
        $this->expectException(InvalidPngException::class);
        new PNGChunks(PNGChunksTest::$invalidPng);
    }

    /**
     * @testdox Should get an array of chunks
     * @test
     */
    public function catchAllChunks()
    {
        $pngFile = PNGChunksTest::$png;
        $png = new PNGChunks($pngFile);
        $chunks = $png->getChunks();

        $this->assertArrayHasKey('IHDR', $chunks);
        $this->assertGreaterThan(0, count($chunks));
    }

    /**
     * @testdox Should get a chunk by key
     * @test
     */
    public function catchChunkByKey()
    {
        $pngFile = PNGChunksTest::$png;
        $png = new PNGChunks($pngFile);
        $chunks = $png->getChunks('tEXt', 'Comment');

        $this->assertArrayHasKey('Comment', $chunks);
        $this->assertGreaterThan(0, count($chunks));
    }

    /**
     * @testdox Should add new chunk
     * @test
     */
    public function addNewTextChunk()
    {
        $pngFile = PNGChunksTest::$png;
        $png = new PNGChunks($pngFile);

        $png->addChunk('custom', 'TestText', 'tEXt');

        $textChunks = $png->getChunks('tEXt');

        $this->assertArrayHasKey('custom', $textChunks);
    }

    /**
     * @testdox Should add new multiple chunks with same key
     * @test
     */
    public function removeTextChunks()
    {
        $pngFile = PNGChunksTest::$png;
        $png = new PNGChunks($pngFile);

        $png->removeChunks('tEXt');

        $textChunks = $png->getChunks('tEXt');

        $this->assertCount(0, $textChunks);
    }

    /**
     * @testdox Should remove a chunk by specified key
     * @test
     */
    public function removeTextChunkByKey()
    {
        $pngFile = PNGChunksTest::$png;
        $png = new PNGChunks($pngFile);

        $png->removeChunks('tEXt', 'Comment');

        $textChunks = $png->getChunks('tEXt');

        $this->assertArrayNotHasKey('Comment', $textChunks);
    }

    /**
     * @testdox Should add new multiple chunks with same key
     * @test
     */
    public function addNewMultipleChunksWithSameKey()
    {
        $pngFile = PNGChunksTest::$png;
        $png = new PNGChunks($pngFile);

        $png
            ->addChunk('custom', 'TestText1')
            ->addChunk('custom', 'TestText2')
            ->addChunk('custom', 'TestText3');

        $textChunks = $png->getChunks('tEXt');

        $this->assertArrayHasKey('custom', $textChunks);
        $this->assertArrayHasKey('custom_2', $textChunks);
        $this->assertArrayHasKey('custom_3', $textChunks);
    }
}
