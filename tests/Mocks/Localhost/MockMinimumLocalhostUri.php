<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Mocks\Localhost;

use Quillstack\Uri\Tests\Mocks\AbstractUriMock;

class MockMinimumLocalhostUri extends AbstractUriMock
{
    public const SCHEME = 'http';
    public const HOST = '127.0.0.1';
    public const PORT = '';
    public const AUTHORITY = self::HOST;
    public const USER = '';
    public const PASS = '';
    public const USER_INFO = '';
    public const PATH = '';
    public const QUERY = '';
    public const FRAGMENT = '';

    public const URI = 'http://127.0.0.1/';
}
