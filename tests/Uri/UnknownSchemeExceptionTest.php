<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Uri;

use Quillstack\UnitTests\AssertExceptions;
use Quillstack\Uri\Factory\Exceptions\UnknownSchemeException;
use Quillstack\Uri\Tests\Mocks\Localhost\MockUnknownSchemeException;

class UnknownSchemeExceptionTest
{
    public function __construct(private MockUnknownSchemeException $mock, private AssertExceptions $assertExceptions)
    {
        //
    }

    public function testToString()
    {
        $this->assertExceptions->expect(UnknownSchemeException::class);

        $this->mock->get();
    }
}
