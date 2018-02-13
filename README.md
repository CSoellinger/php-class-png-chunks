# PHP PngChunk Class [![Build Status](https://travis-ci.org/CSoellinger/php-class-png-chunks.svg?branch=master)](https://travis-ci.org/CSoellinger/php-class-png-chunks) [![Coverage Status](https://coveralls.io/repos/github/CSoellinger/php-class-png-chunks/badge.svg?branch=master)](https://coveralls.io/github/CSoellinger/php-class-png-chunks?branch=master)

Get, add or remove chunks to PNG. This class is more a personal helper for other projects but could be nice for others too. Feel free to extend the code with new features.

## Installation

### With composer

```shell
composer require pixelcrab/png-chunks
```

### With source

Download the source code from releases or the file **PngChunks.php** directly from the repository. Include the file in your project and start using it.

## Usage

```php
<?php
// Use namespace
use PHPPNG\PNGChunks\PNGChunks;

// Initialize class. Param is a valid PNG file.
$png = new PngChunk(__DIR__ . '/image.png');

// Fetch all tEXt chunks
$chunks = $png->getChunks('tEXt');

// Remove all tEXt chunks
$png->removeChunks('tEXt');

// Add new tEXt chunk
$png->addChunk('tEXt', 'custom', 'my value');

// Some methods are chainable
$png
  ->removeChunks('tEXt')
  ->addChunk('tEXt', 'custom', 'my value');
```

## Want spend me some BTC, ETC or BTH

- Bitcoin (BTC): 19FXuMSR1yoApqZ9VkY1e8bhxHp4fqK4ZB
- Ether (ETC): 0x45dAC2c1647B505Dc3a4E48FAa3443bbEAf6eBF2
- Bitcoin Cash (BTH): qpdgpgp78r5ql8c9tjtkc4ex82s09hc4a5tsralx4g
