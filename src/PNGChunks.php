<?php
/**
 * A simple class to get, add or remove chunks from a PNG file.
 *
 * With help from:
 * Andrew Moore https://stackoverflow.com/a/2190438
 * leonbloy https://stackoverflow.com/a/8856458
 *
 * @link        https://github.com/CSoellinger/php-class-png-chunks for the canonical source repository
 * @license     https://github.com/CSoellinger/php-class-png-chunks/blob/master/LICENSE MIT License
 * @category    PHPPNG
 * @package     PNGChunks
 * @author      Christopher Söllinger <cs@pixelcrab.at>
 */

namespace PHPPNG\PNGChunks;

use PHPPNG\PNGChunks\Adapter\FileReader;
use PHPPNG\PNGChunks\Exception\InvalidPngException;

class PNGChunks
{
    /**
     * Array with chunk offsets and sizes to read it after
     *
     * @var string[] $chunks
     */
    private $chunks;

    /**
     * Handle file opening and reading
     *
     * @var FileReader
     */
    private $fileReader;

    /**
     * Load a PNG file and split the chunks
     *
     * @param string $file PNG file
     */
    public function __construct($file)
    {
        $this->fileReader = new FileReader($file);
        $this->readChunks();
    }

    /**
     * @internal We split the PNGs chunks and safe the offsets and sizes
     *
     * @return PngChunks $this
     */
    private function readChunks(): PngChunks
    {
        $this->fileReader->reOpenFile();

        $this->chunks = array();

        // Read the magic bytes and verify
        $header = $this->fileReader->readFile(8);

        if ($header != "\x89PNG\x0d\x0a\x1a\x0a") {
            throw new InvalidPngException($this->fileReader->getFilePath());
        }

        // Loop through the chunks. Byte 0-3 is length, Byte 4-7 is type
        $chunkHeader = $this->fileReader->readFile(8);

        while ($chunkHeader) {
            // Extract length and type from binary data
            $chunk = @unpack('Nsize/a4type', $chunkHeader);

            // Store position into internal array
            if (!isset($this->chunks[$chunk['type']])) {
                $this->chunks[$chunk['type']] = array();
            }

            $this->chunks[$chunk['type']][] = array(
                'offset' => ftell($this->fileReader->getFile()),
                'size' => $chunk['size'],
            );

            // Skip to next chunk (over body and CRC)
            fseek($this->fileReader->getFile(), $chunk['size'] + 4, SEEK_CUR);

            // Read next chunk header
            $chunkHeader = $this->fileReader->readFile(8);
        }

        return $this;
    }

    private function extractChunks($type, $key, &$chunks)
    {
        // Iterate over all chunks from one type
        foreach ($this->chunks[$type] as $chunk) {
            if ($chunk['size'] > 0) {
                $this->extractChunk($chunk, $type, $key, $chunks);
            }
        }
    }

    private function extractChunk($chunk, $type, $key, &$chunks)
    {
        // Point file cursor to chunk offset
        fseek($this->fileReader->getFile(), $chunk['offset'], SEEK_SET);
        // And read the chunk from offset to chunk size
        $chunkraw = $this->fileReader->readFile($chunk['size']);

        // If we're looking for a key we skip by default
        $skip = $key === null ? false : true;

        // If we have a sign for a key we will handle it
        if (strpos($chunkraw, "\000") > 0) {
            // Split the key and body
            $chunkkey = substr($chunkraw, 0, strpos($chunkraw, "\000"));
            $chunkbody = substr($chunkraw, strpos($chunkraw, "\000") + 1);

            // Decide if we skip if we are looking for a key
            if ($key === $chunkkey) {
                $skip = false;
            }

            if ($skip === false) {
                if (isset($chunks[$type][$chunkkey])) {
                    $cnt = 2;
                    $chunkkeyNew = $chunkkey . '_' . $cnt;

                    while (isset($chunks[$type][$chunkkeyNew])) {
                        $cnt++;
                        $chunkkeyNew = $chunkkey . '_' . $cnt;
                    }

                    $chunkkey = $chunkkeyNew;
                }
                $chunks[$type][$chunkkey] = $chunkbody;
            }
        }

        if ($skip === false) {
            $chunks[$type][] = $chunkraw;
        }
    }

    /**
     * Get chunks from PNG file
     *
     * @param string|string[] $types Chunk type like tEXt
     * @param string $key Filter for chunk key
     * @return string[] $chunks
     */
    public function getChunks($types = null, $key = null)
    {
        // If no types given we use all collected keys from $chunks
        if ($types === null) {
            $types = array_keys($this->chunks);
        }

        // If given param is only a string we will cast it to an array
        if (!is_array($types) && is_string($types)) {
            $types = array($types);
        }

        $chunks = array();
        // Iterate over all chunk types
        foreach ($types as $type) {
            if (isset($this->chunks[$type]) && $this->chunks[$type] !== null) {
                $chunks[$type] = array();
                $this->extractChunks($type, $key, $chunks);
            }
        }

        // If we collected only one type we reduce the array to make it simpler
        if (count($types) === 1 && isset($chunks[$types[0]])) {
            $chunks = $chunks[$types[0]];
        }

        return $chunks;
    }

    /**
     * Remove chunks from the PNG
     * @todo This function is more a hack than a solution... but it was the easiest way at the moment. Refactor it.
     *
     * @param string|string[] $types Chunk type to remove
     * @param string $key Chunk type key to remove
     * @return PngChunks $this
     */
    public function removeChunks($types = 'tEXt', $key = null)
    {

        // If given param is only a string we will cast it to an array
        if (!is_array($types) && is_string($types)) {
            $types = array($types);
        }

        // Read the full png
        $png = file_get_contents($this->fileReader->getFilePath());

        $newPng = substr($png, 0, 8);
        $ipos = 8;
        // Loop through the chunks. Byte 0-3 is length, Byte 4-7 is type
        $chunkHeader = substr($png, $ipos, 8);
        $ipos = $ipos + 8;
        while ($chunkHeader) {
            // Extract length and type from binary data
            $chunk = @unpack('Nsize/a4type', $chunkHeader);
            $skip = false;
            if (in_array($chunk['type'], $types)) {
                $data = substr($png, $ipos, $chunk['size']);
                $sections = explode("\0", $data);

                if ($sections[0] == $key || $key === null) {
                    $skip = true;
                }
            }

            // Extract the data and the CRC
            $data = substr($png, $ipos, $chunk['size'] + 4);
            $ipos = $ipos + $chunk['size'] + 4;
            // Add in the header, data, and CRC
            if (!$skip) {
                $newPng = $newPng . $chunkHeader . $data;
            }

            // Read next chunk header
            $chunkHeader = substr($png, $ipos, 8);
            $ipos = $ipos + 8;
        }

        file_put_contents($this->fileReader->getFilePath(), $newPng);

        // Re-read the chunks
        $this->readChunks();

        return $this;
    }

    /**
     * Add a new chunk to the PNG file
     *
     * @param string $key Give it a key name
     * @param string $text Text value from the new chunk
     * @param string $type Chunktype, by default tEXt
     * @return PngChunks $this
     */
    public function addChunk($key, $text, $type = 'tEXt'): PngChunks
    {
        $file = $this->fileReader->getFilePath();

        // Get our new chunk
        $newchunk = $this->createChunk($key, $text, $type);

        // Read the full png
        $png = file_get_contents($file);
        $len = strlen($png);

        // And write it with new chunk part
        file_put_contents($file, substr($png, 0, $len - 12) . $newchunk . substr($png, $len - 12));

        // Re-read the chunks
        $this->readChunks();

        return $this;
    }

    /**
     * @internal Create a new PNG chunk part
     *
     * @param string $key Give it a key name
     * @param string $text Text value "doubleClick"from the new chunk
     * @param string $type Chunktype, by default tEXt
     * @return string
     */
    private function createChunk($key, $text, $type = 'tEXt'): string
    {
        $chunkdata = $key . "\0" . $text;
        $crc = pack("N", crc32($type . $chunkdata));
        $len = pack("N", strlen($chunkdata));
        return $len . $type . $chunkdata . $crc;
    }
}

// $png = new PNGChunks('file.png');
