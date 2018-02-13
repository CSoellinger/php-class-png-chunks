<?php
/**
 * FileOpenException implementation
 *
 * @link        https://github.com/CSoellinger/php-class-png-chunks for the canonical source repository
 * @license     https://github.com/CSoellinger/php-class-png-chunks/blob/master/LICENSE MIT License
 * @category    PHPPNG
 * @package     PNGChunks
 * @author      Christopher Söllinger <cs@pixelcrab.at>
 */

namespace PHPPNG\PNGChunks\Exception\Reader;

/**
 * FileOpenException
 *
 * @category    PHPPNG
 * @package     PNGChunks
 */
class FileOpenException extends \Exception
{
    /**
     * Throw "Could not open file" error
     *
     * @param string $path Filepath which can not be opened
     */
    public function __construct($path)
    {
        parent::__construct(sprintf(
            'Could not open file %1$s',
            $path
        ));
    }
}
