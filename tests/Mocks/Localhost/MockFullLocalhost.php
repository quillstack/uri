<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Mocks\Localhost;

use Quillstack\Uri\Tests\Mocks\AbstractUriMock;

final class MockFullLocalhost extends AbstractUriMock
{
    public const SCHEME = 'http';
    public const HOST = '127.0.0.1';
    public const PORT = 8080;
    public const AUTHORITY = self::HOST . ':' . self::PORT;
    public const USER = 'user';
    public const PASS = 'pass';
    public const USER_INFO = self::USER . ':' . self::PASS;
    public const PATH = 'path';
    public const QUERY = 'query=string';
    public const FRAGMENT = 'fragment';

    public const URI = 'http://user:pass@127.0.0.1:8080/path?query=string#fragment';
}
