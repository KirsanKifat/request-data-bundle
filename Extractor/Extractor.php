<?php

namespace KirsanKifat\RequestDataBundle\Extractor;

use KirsanKifat\RequestDataBundle\Formats;
use KirsanKifat\RequestDataBundle\TypeConverter\TypeConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class Extractor implements ExtractorInterface
{
    /**
     * @var TypeConverterInterface
     */
    private $converter;

    public function __construct(TypeConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function extractData(Request $request, string $format)
    {
        if (Formats::FORM === $format) {
            return array_merge($this->converter->convert($request->query->all()), $request->files->all() + $request->request->all());
        }

        return array_merge($this->converter->convert($request->query->all()), $request->getContent());
    }

    /**
     * {@inheritdoc}
     */
    public function extractFormat(Request $request): ?string
    {
        if (Request::METHOD_GET === $request->getMethod()) {
            return Formats::FORM;
        }

        $format = $request->getFormat($request->headers->get('content-type'));

        if (!\in_array($format, static::getSupportedFormats())) {
            return null;
        }

        return $format;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedFormats(): array
    {
        return [Formats::FORM, Formats::JSON, Formats::XML];
    }
}
