<?php
/**
 * NoFileOpenException implementation
 *
 * @link        https://github.com/CSoellinger/php-class-png-chunks for the canonical source repository
 * @license     https://github.com/CSoellinger/php-class-png-chunks/blob/master/LICENSE MIT License
 * @category    PHPPNG
 * @package     PNGChunks
 * @author      Christopher Söllinger <cs@pixelcrab.at>
 */

namespace PHPPNG\PNGChunks\Exception\Reader;

/**
 * NoFileOpenException
 *
 * @category    PHPPNG
 * @package     PNGChunks
 */
class NoFileOpenException extends \Exception
{
    /**
     * Throw "No file open" error
     */
    public function __construct()
    {
        parent::__construct('No file open');
    }
}
