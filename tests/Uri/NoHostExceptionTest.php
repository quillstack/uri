<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Uri;

use Quillstack\UnitTests\AssertExceptions;
use Quillstack\Uri\Factory\Exceptions\UnknownHostException;
use Quillstack\Uri\Tests\Mocks\Localhost\MockNoHostException;

class NoHostExceptionTest
{
    public function __construct(private MockNoHostException $mock, private AssertExceptions $assertExceptions)
    {
        //
    }

    public function testToString()
    {
        $this->assertExceptions->expect(UnknownHostException::class);

        $this->mock->get();
    }
}
