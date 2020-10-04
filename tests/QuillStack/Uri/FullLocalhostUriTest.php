<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use QuillStack\Mocks\Localhost\MockFullLocalhost;

final class FullLocalhostUriTest extends TestCase
{
    private UriInterface $uri;

    protected function setUp(): void
    {
        $this->uri = (new MockFullLocalhost())->get();
    }

    public function testToString()
    {
        $this->assertEquals(MockFullLocalhost::URI, (string) $this->uri);
    }

    public function testGetScheme()
    {
        $this->assertEquals(MockFullLocalhost::SCHEME, $this->uri->getScheme());
    }

    public function testGetAuthority()
    {
        $this->assertEquals(MockFullLocalhost::AUTHORITY, $this->uri->getAuthority());
    }

    public function testGetUserInfo()
    {
        $this->assertEquals(MockFullLocalhost::USER_INFO, $this->uri->getUserInfo());
    }

    public function testGetHost()
    {
        $this->assertEquals(MockFullLocalhost::HOST, $this->uri->getHost());
    }

    public function testGetPort()
    {
        $this->assertEquals(MockFullLocalhost::PORT, $this->uri->getPort());
    }

    public function testGetPath()
    {
        $this->assertEquals(MockFullLocalhost::PATH, $this->uri->getPath());
    }

    public function testGetQuery()
    {
        $this->assertEquals(MockFullLocalhost::QUERY, $this->uri->getQuery());
    }

    public function testGetFragment()
    {
        $this->assertEquals(MockFullLocalhost::FRAGMENT, $this->uri->getFragment());
    }

    public function testWithScheme()
    {
        $uri = $this->uri->withScheme('https');

        $this->assertEquals(MockFullLocalhost::SCHEME, $this->uri->getScheme());
        $this->assertEquals('https', $uri->getScheme());
    }
}
