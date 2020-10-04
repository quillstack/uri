<?php

declare(strict_types=1);

namespace QuillStack\Http\Uri\Factory\Exceptions;

use QuillStack\ValidationExceptionInterface;

final class UnknownSchemeException extends UriException implements ValidationExceptionInterface
{
}
