<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Mocks\Localhost;

use Quillstack\Uri\Tests\Mocks\AbstractUriMock;

class MockUnknownSchemeException extends AbstractUriMock
{
    public const URI = 'tar+gz://127.0.0.1/';
}
