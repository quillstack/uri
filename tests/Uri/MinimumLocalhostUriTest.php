<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Uri;

use Psr\Http\Message\UriInterface;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\Uri\Tests\Mocks\Localhost\MockMinimumLocalhostUri;

class MinimumLocalhostUriTest
{
    private UriInterface $uri;

    public function __construct(private MockMinimumLocalhostUri $mock, private AssertEqual $assertEqual)
    {
        $this->uri = $this->mock->get();
    }

    public function testToString()
    {
        $this->assertEqual->equal(MockMinimumLocalhostUri::URI, (string) $this->uri);
    }
}
