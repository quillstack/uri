<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use QuillStack\Http\Uri\Uri;

final class UriTest extends TestCase
{
    public function testGetScheme()
    {
        $uri = new Uri('http', '', '', '', 80, '', '');

        $this->assertEquals('http', $uri->getScheme());
    }
}
