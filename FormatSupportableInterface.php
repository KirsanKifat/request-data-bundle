<?php

namespace KirsanKifat\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
interface FormatSupportableInterface
{
    /**
     * Return supported formats.
     *
     * @return array
     */
    public static function getSupportedFormats(): array;
}
