<?php

declare(strict_types=1);

namespace QuillStack\Mocks\Localhost;

use QuillStack\Mocks\AbstractUriMock;

final class MockNoHostException extends AbstractUriMock
{
    public const URI = 'http:/127.0.0.1/';
}
