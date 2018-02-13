<?php
/**
 * InvalidPngException implementation
 *
 * @link        https://github.com/CSoellinger/php-class-png-chunks for the canonical source repository
 * @license     https://github.com/CSoellinger/php-class-png-chunks/blob/master/LICENSE MIT License
 * @category    PHPPNG
 * @package     PNGChunks
 * @author      Christopher Söllinger <cs@pixelcrab.at>
 */

namespace PHPPNG\PNGChunks\Exception;

/**
 * InvalidPngException
 *
 * @internal
 * @category    PHPPNG
 * @package     PNGChunks
 */
class InvalidPngException extends \Exception
{
    /**
     * Throw a "NOT a PNG file" error
     *
     * @param string $path A filepath
     */
    public function __construct($path)
    {
        parent::__construct(sprintf(
            'Not a PNG file %1$s',
            $path
        ));
    }
}
