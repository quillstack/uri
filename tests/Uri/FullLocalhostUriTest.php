<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Uri;

use Psr\Http\Message\UriInterface;
use Quillstack\Uri\Tests\Mocks\Localhost\MockFullLocalhost;
use Quillstack\UnitTests\AssertEqual;

class FullLocalhostUriTest
{
    private UriInterface $uri;

    public function __construct(private MockFullLocalhost $mock, private AssertEqual $assertEqual)
    {
        $this->uri = $this->mock->get();
    }

    public function testToString()
    {
        $this->assertEqual->equal(MockFullLocalhost::URI, (string) $this->uri);
    }

    public function testGetScheme()
    {
        $this->assertEqual->equal(MockFullLocalhost::SCHEME, $this->uri->getScheme());
    }

    public function testGetAuthority()
    {
        $this->assertEqual->equal(MockFullLocalhost::AUTHORITY, $this->uri->getAuthority());
    }

    public function testGetUserInfo()
    {
        $this->assertEqual->equal(MockFullLocalhost::USER_INFO, $this->uri->getUserInfo());
    }

    public function testGetHost()
    {
        $this->assertEqual->equal(MockFullLocalhost::HOST, $this->uri->getHost());
    }

    public function testGetPort()
    {
        $this->assertEqual->equal(MockFullLocalhost::PORT, $this->uri->getPort());
    }

    public function testGetPath()
    {
        $this->assertEqual->equal(MockFullLocalhost::PATH, $this->uri->getPath());
    }

    public function testGetQuery()
    {
        $this->assertEqual->equal(MockFullLocalhost::QUERY, $this->uri->getQuery());
    }

    public function testGetFragment()
    {
        $this->assertEqual->equal(MockFullLocalhost::FRAGMENT, $this->uri->getFragment());
    }

    public function testWithScheme()
    {
        $uri = $this->uri->withScheme('https');

        $this->assertEqual->equal(MockFullLocalhost::SCHEME, $this->uri->getScheme());
        $this->assertEqual->equal('https', $uri->getScheme());
    }

    public function testWithUserInfo()
    {
        $uri = $this->uri->withUserInfo('test-user');

        $this->assertEqual->equal(MockFullLocalhost::USER_INFO, $this->uri->getUserInfo());
        $this->assertEqual->equal('test-user', $uri->getUserInfo());
    }

    public function testWithUserInfoWithPassword()
    {
        $uri = $this->uri->withUserInfo('test-user:new-password');

        $this->assertEqual->equal(MockFullLocalhost::USER_INFO, $this->uri->getUserInfo());
        $this->assertEqual->equal('test-user:new-password', $uri->getUserInfo());
    }

    public function testWithHost()
    {
        $uri = $this->uri->withHost('localhost');

        $this->assertEqual->equal(MockFullLocalhost::HOST, $this->uri->getHost());
        $this->assertEqual->equal('localhost', $uri->getHost());
    }

    public function testWithPort()
    {
        $uri = $this->uri->withPort(8081);

        $this->assertEqual->equal(MockFullLocalhost::PORT, $this->uri->getPort());
        $this->assertEqual->equal(8081, $uri->getPort());
    }

    public function testWithPath()
    {
        $uri = $this->uri->withPath('new-path');

        $this->assertEqual->equal(MockFullLocalhost::PATH, $this->uri->getPath());
        $this->assertEqual->equal('new-path', $uri->getPath());
    }

    public function testWithQuery()
    {
        $uri = $this->uri->withQuery('x=3&y=6');

        $this->assertEqual->equal(MockFullLocalhost::QUERY, $this->uri->getQuery());
        $this->assertEqual->equal('x=3&y=6', $uri->getQuery());
    }

    public function testWithFragment()
    {
        $uri = $this->uri->withFragment('strawberry');

        $this->assertEqual->equal(MockFullLocalhost::FRAGMENT, $this->uri->getFragment());
        $this->assertEqual->equal('strawberry', $uri->getFragment());
    }
}
