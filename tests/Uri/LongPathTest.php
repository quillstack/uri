<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Uri;

use Psr\Http\Message\UriInterface;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\Uri\Tests\Mocks\Localhost\LongPathLocalhost;

class LongPathTest
{
    private UriInterface $uri;

    public function __construct(private LongPathLocalhost $mock, private AssertEqual $assertEqual)
    {
        $this->uri = $this->mock->get();
    }

    public function testToString()
    {
        $this->assertEqual->equal(LongPathLocalhost::URI, (string) $this->uri);
        $this->assertEqual->equal(LongPathLocalhost::PATH, $this->uri->getPath());
    }
}
