<?php declare(strict_types=1);

namespace {{namespace}}\Test\PHPUnit;

use PHPUnit\Framework\TestCase;

abstract class {{pluginName}}TestCase extends TestCase
{
    protected function getSimpleMock($originalClassName)
    {
        return $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
