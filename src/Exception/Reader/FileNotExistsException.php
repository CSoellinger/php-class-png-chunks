<?php
/**
 * FileNotExistsException implementation
 *
 * @link        https://github.com/CSoellinger/php-class-png-chunks for the canonical source repository
 * @license     https://github.com/CSoellinger/php-class-png-chunks/blob/master/LICENSE MIT License
 * @category    PHPPNG
 * @package     PNGChunks
 * @author      Christopher Söllinger <cs@pixelcrab.at>
 */

namespace PHPPNG\PNGChunks\Exception\Reader;

/**
 * FileNotExistsException
 *
 * @category    PHPPNG
 * @package     PNGChunks
 */
class FileNotExistsException extends \Exception
{
    /**
     * Throw "File not exists" error
     *
     * @param string $path Filepath which not exists
     */
    public function __construct($path)
    {
        parent::__construct(sprintf(
            'Given path is not an existing file %1$s',
            $path
        ));
    }
}
