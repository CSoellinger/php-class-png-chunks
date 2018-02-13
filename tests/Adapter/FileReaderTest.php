<?php
namespace Tests\PHPPNG\PNGChunks;

use PHPPNG\PNGChunks\Adapter\FileReader;
use PHPPNG\PNGChunks\Exception\Reader\FileNotExistsException;
use PHPPNG\PNGChunks\Exception\Reader\NoFileOpenException;

use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{

    public static $pngOrig = __DIR__ . '/../../assets/test.png';

    /**
     * @testdox Should create an instance with an existing file
     * @test
     */
    public function canBeCreatedFromExistingFile()
    {
        $pngFile = FileReaderTest::$pngOrig;
        $this->assertInstanceOf(
            FileReader::class,
            new FileReader($pngFile)
        );
    }

    /**
     * @testdox Should throw exception when giving a non existing file
     * @test
     */
    public function cannotBeCreatedFromNotExistingFile()
    {
        $this->expectException(FileNotExistsException::class);
        new FileReader('no-file.png');
    }

    /**
     * @testdox Should throw exception when giving an empty file path
     * @test
     */
    public function cannotBeCreatedFromEmptyFilePath()
    {
        $this->expectException(FileNotExistsException::class);
        new FileReader();
    }

    /**
     * @testdox ReOpen file and check if it is still open
     * @test
     */
    public function reOpenFileAndCheckState()
    {
        $pngFile = FileReaderTest::$pngOrig;
        $fileReader = new FileReader($pngFile);

        $this->assertTrue($fileReader->isFileOpen());
        $fileReader->reOpenFile();
        $this->assertTrue($fileReader->isFileOpen());
    }

    /**
     * @testdox Get file path
     * @test
     */
    public function getFilePath()
    {
        $pngFile = FileReaderTest::$pngOrig;
        $fileReader = new FileReader($pngFile);

        $this->assertFileEquals($pngFile, $fileReader->getFilePath());
    }

    /**
     * @testdox Get filepointer
     * @test
     */
    public function getFilePointer()
    {
        $pngFile = FileReaderTest::$pngOrig;
        $fileReader = new FileReader($pngFile);
        $this->assertNotFalse($fileReader->getFile());
    }

    /**
     * @testdox Read from file
     * @test
     */
    public function readFromFile()
    {
        $pngFile = FileReaderTest::$pngOrig;
        $fileReader = new FileReader($pngFile);
        $this->assertNotFalse($fileReader->readFile(1));
    }

    /**
     * @testdox Read from file
     * @test
     */
    public function cannotReadFromFileWhenNotOpen()
    {
        $pngFile = FileReaderTest::$pngOrig;
        $fileReader = new FileReader($pngFile);
        $fileReader->closeFile();

        $this->expectException(NoFileOpenException::class);
        $fileReader->readFile(1);
    }

    /**
     * @testdox Read from file
     * @test
     */
    public function cannotGetFilePointerWhenNotOpen()
    {
        $pngFile = FileReaderTest::$pngOrig;
        $fileReader = new FileReader($pngFile);
        $fileReader->closeFile();

        $this->expectException(NoFileOpenException::class);
        $fileReader->getFile();
    }
}
