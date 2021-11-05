<?php

declare(strict_types=1);

use Quillstack\Uri\Tests\Uri\FullLocalhostUriTest;
use Quillstack\Uri\Tests\Uri\LongPathTest;
use Quillstack\Uri\Tests\Uri\MinimumLocalhostUriTest;
use Quillstack\Uri\Tests\Uri\NoHostExceptionTest;
use Quillstack\Uri\Tests\Uri\UnknownSchemeExceptionTest;

return [
    FullLocalhostUriTest::class,
    LongPathTest::class,
    MinimumLocalhostUriTest::class,
    NoHostExceptionTest::class,
    UnknownSchemeExceptionTest::class,
];
