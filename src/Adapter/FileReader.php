<?php
/**
 * Simple file reading wrapper which also saves the filepath
 *
 * @link        https://github.com/CSoellinger/php-class-png-chunks for the canonical source repository
 * @license     https://github.com/CSoellinger/php-class-png-chunks/blob/master/LICENSE MIT License
 * @category    PHPPNG
 * @package     PNGChunks
 * @author      Christopher Söllinger <cs@pixelcrab.at>
 * @version     1.0.0
 */

namespace PHPPNG\PNGChunks\Adapter;

use PHPPNG\PNGChunks\Exception\Reader\FileNotExistsException;
use PHPPNG\PNGChunks\Exception\Reader\FileOpenException;
use PHPPNG\PNGChunks\Exception\Reader\NoFileOpenException;

class FileReader
{
    /**
     * Filepath
     *
     * @var string
     */
    private $file;

    /**
     * File pointer ressource
     *
     * @var resource
     */
    private $fpointer;

    /**
     * Open a new file reader
     *
     * @param string $file Filepath to open
     */
    public function __construct($file = null)
    {
        $this
            ->setFilePath($file)
            ->openFile();
    }

    /**
     * Close file pointer on destruct
     */
    public function __destruct()
    {
        $this->closeFile();
    }

    /**
     * Returns filepath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->file;
    }

    /**
     * Set a new filepath and checks if file exists
     *
     * @param string $file Filepath to set
     * @return FileReader
     */
    public function setFilePath($file)
    {
        if (!file_exists($file)) {
            throw new FileNotExistsException($file);
        }

        $this->file = $file;

        return $this;
    }

    /**
     * Opens a file pointer
     *
     * @return FileReader
     */
    public function openFile()
    {
        $this->closeFile();
        
        // Open the file
        $this->fpointer = fopen($this->file, 'r+');
        
        // @codeCoverageIgnoreStart
        if (!$this->fpointer) {
            throw new FileOpenException($this->file);
        }
        // @codeCoverageIgnoreEnd

        return $this;
    }

    /**
     * Close file pointer if open
     *
     * @return FileReader
     */
    public function closeFile()
    {
        if ($this->isFileOpen()) {
            fclose($this->fpointer);
            unset($this->fpointer);
        }
        
        return $this;
    }
    
    /**
     * Close and reopen file pointer
     *
     * @return FileReader
     */
    public function reOpenFile()
    {
        if ($this->isFileOpen()) {
            $this->closeFile();
        }
        $this->openFile();
        
        return $this;
    }

    /**
     * Read from file pointer
     *
     * @param string $length Length to read
     * @return string
     */
    public function readFile($length)
    {
        if (!isset($this->fpointer)) {
            throw new NoFileOpenException;
        }
        return fread($this->fpointer, $length);
    }
    
    /**
     * Get file pointer ressource
     *
     * @return ressource
     */
    public function getFile()
    {
        if (!isset($this->fpointer)) {
            throw new NoFileOpenException;
        }
        return $this->fpointer;
    }
    
    /**
     * Checks if file pointer is open
     *
     * @return bool
     */
    public function isFileOpen()
    {
        return isset($this->fpointer);
    }
}
