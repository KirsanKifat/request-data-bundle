<?php

namespace KirsanKifat\RequestDataBundle\Tests\Fixtures;

use KirsanKifat\RequestDataBundle\Formats;
use KirsanKifat\RequestDataBundle\FormatSupportableInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class TestFormatSupportableRequestData implements FormatSupportableInterface
{
    /**
     * @var string|null
     */
    public $foo;

    /**
     * {@inheritdoc}
     */
    public static function getSupportedFormats(): array
    {
        return [Formats::JSON];
    }
}
