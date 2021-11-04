<?php

declare(strict_types=1);

namespace QuillStack\Uri;

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Uri\Factory\Exceptions\UnknownSchemeException;
use QuillStack\Mocks\Localhost\MockUnknownSchemeException;

final class UnknownSchemeExceptionTest extends TestCase
{
    public function testToString()
    {
        $this->expectException(UnknownSchemeException::class);

        (new MockUnknownSchemeException())->get();
    }
}
