<?php

declare(strict_types=1);

namespace QuillStack\Http\Uri\Factory\Exceptions;

use QuillStack\ValidationExceptionInterface;

final class UnknownHostException extends UriException implements ValidationExceptionInterface
{
}
