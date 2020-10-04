<?php

declare(strict_types=1);

namespace QuillStack\Uri;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use QuillStack\Mocks\Localhost\MockMinimumLocalhostUri;

final class MinimumLocalhostUriTest extends TestCase
{
    private UriInterface $uri;

    protected function setUp(): void
    {
        $this->uri = (new MockMinimumLocalhostUri())->get();
    }

    public function testToString()
    {
        $this->assertEquals(MockMinimumLocalhostUri::URI, (string) $this->uri);
    }
}
