<?php declare(strict_types=1);

namespace {{namespace}}\Test\PHPUnit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class {{pluginName}}TestCase extends TestCase
{
    /**
     * @template T of object
     * @param class-string<T> $originalClassName
     * @return MockObject
     */
    protected function getSimpleMock(string $originalClassName): MockObject
    {
        return $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
