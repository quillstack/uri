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

    public function testWithUserInfo()
    {
        $uri = $this->uri->withUserInfo('test-user');

        $this->assertEquals(MockFullLocalhost::USER_INFO, $this->uri->getUserInfo());
        $this->assertEquals('test-user', $uri->getUserInfo());
    }

    public function testWithUserInfoWithPassword()
    {
        $uri = $this->uri->withUserInfo('test-user:new-password');

        $this->assertEquals(MockFullLocalhost::USER_INFO, $this->uri->getUserInfo());
        $this->assertEquals('test-user:new-password', $uri->getUserInfo());
    }

    public function testWithHost()
    {
        $uri = $this->uri->withHost('localhost');

        $this->assertEquals(MockFullLocalhost::HOST, $this->uri->getHost());
        $this->assertEquals('localhost', $uri->getHost());
    }

    public function testWithPort()
    {
        $uri = $this->uri->withPort(8081);

        $this->assertEquals(MockFullLocalhost::PORT, $this->uri->getPort());
        $this->assertEquals(8081, $uri->getPort());
    }

    public function testWithPath()
    {
        $uri = $this->uri->withPath('new-path');

        $this->assertEquals(MockFullLocalhost::PATH, $this->uri->getPath());
        $this->assertEquals('new-path', $uri->getPath());
    }

    public function testWithQuery()
    {
        $uri = $this->uri->withQuery('x=3&y=6');

        $this->assertEquals(MockFullLocalhost::QUERY, $this->uri->getQuery());
        $this->assertEquals('x=3&y=6', $uri->getQuery());
    }

    public function testWithFragment()
    {
        $uri = $this->uri->withFragment('strawberry');

        $this->assertEquals(MockFullLocalhost::FRAGMENT, $this->uri->getFragment());
        $this->assertEquals('strawberry', $uri->getFragment());
    }
}
