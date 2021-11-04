<?php

declare(strict_types=1);

namespace QuillStack\Uri;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use QuillStack\Mocks\Localhost\LongPathLocalhost;
use QuillStack\Mocks\Localhost\MockMinimumLocalhostUri;

final class LongPathTest extends TestCase
{
    private UriInterface $uri;

    protected function setUp(): void
    {
        $this->uri = (new LongPathLocalhost())->get();
    }

    public function testToString()
    {
        $this->assertEquals(LongPathLocalhost::URI, (string) $this->uri);
        $this->assertEquals(LongPathLocalhost::PATH, $this->uri->getPath());
    }
}
