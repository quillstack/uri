<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Mocks;

use Psr\Http\Message\UriInterface;
use Quillstack\Uri\Factory\UriFactory;
use Quillstack\Uri\Uri;

abstract class AbstractUriMock
{
    public function __construct(private UriFactory $uriFactory)
    {
        //
    }

    /**
     * @return Uri
     */
    public function get(): UriInterface
    {
        return $this->uriFactory->createUri(static::URI);
    }
}
