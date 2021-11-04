<?php

declare(strict_types=1);

namespace QuillStack\Uri;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Uri\Factory\Exceptions\UnknownHostException;
use QuillStack\Mocks\Localhost\MockNoHostException;

final class NoHostExceptionTest extends TestCase
{
    public function testToString()
    {
        $this->expectException(UnknownHostException::class);

        (new MockNoHostException())->get();
    }
}
