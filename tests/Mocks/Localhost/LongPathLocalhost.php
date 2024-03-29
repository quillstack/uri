<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Mocks\Localhost;

use Quillstack\Uri\Tests\Mocks\AbstractUriMock;

final class LongPathLocalhost extends AbstractUriMock
{
    public const PATH = 'path1/path2/path3/path4/path5/path6';

    public const URI = 'http://user:pass@127.0.0.1:8080/' . self::PATH;
}
