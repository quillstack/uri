<?php

declare(strict_types=1);

namespace QuillStack\Mocks\Localhost;

use QuillStack\Mocks\AbstractUriMock;

final class MockUnknownSchemeException extends AbstractUriMock
{
    public const URI = 'tar+gz://127.0.0.1/';
}
