<?php

namespace KirsanKifat\RequestDataBundle\Tests\Extractor;

use KirsanKifat\RequestDataBundle\Exception\NotSupportedFormatException;
use KirsanKifat\RequestDataBundle\Extractor\ExtractorInterface;
use KirsanKifat\RequestDataBundle\Formats;
use KirsanKifat\RequestDataBundle\Mapper\Mapper;
use KirsanKifat\RequestDataBundle\Mapper\MapperInterface;
use KirsanKifat\RequestDataBundle\Tests\Fixtures\TestFormatSupportableRequestData;
use KirsanKifat\RequestDataBundle\Tests\Fixtures\TestRequestData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
class MapperTest extends TestCase
{
    /**
     * @var ExtractorInterface|MockObject
     */
    private $extractor;

    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var MapperInterface|MockObject
     */
    private $mapper;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->extractor = $this->getMockBuilder(ExtractorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapper = new Mapper($this->extractor, $this->serializer, new PropertyAccessor());
    }

    /**
     * Test if mapper implements necessary interfaces.
     */
    public function testInterface()
    {
        $this->assertInstanceOf(MapperInterface::class, $this->mapper);
    }

    /**
     * Tests if mapper throws unsupported format exception.
     */
    public function testUnsupportedRequestFormatException()
    {
        $this->extractor
            ->method('extractFormat')
            ->willReturn(Formats::FORM);

        $this->expectException(NotSupportedFormatException::class);

        $this->mapper->map(new Request(), new TestFormatSupportableRequestData());
    }

    /**
     * Test if mapper handles form requests correctly.
     */
    public function testFormRequestMapping()
    {
        $this->extractor
            ->method('extractFormat')
            ->willReturn(Formats::FORM);

        $this->extractor
            ->method('extractData')
            ->willReturn(['foo' => 'bar']);

        $this->serializer
            ->expects($this->never())
            ->method('deserialize')
            ->willReturn(null);

        $requestData = new TestRequestData();

        $this->mapper->map(new Request(), $requestData);

        $this->assertEquals('bar', $requestData->foo);
    }

    /**
     * Test if mapper handles not form requests correctly.
     */
    public function testNotFormRequestMapping()
    {
        $this->extractor
            ->method('extractFormat')
            ->willReturn(Formats::JSON);

        $this->extractor
            ->method('extractData')
            ->willReturn('{"foo":"bar"}');

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn(null);

        $this->mapper->map(new Request(), new TestRequestData());
    }
}
