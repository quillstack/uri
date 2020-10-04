<?php

declare(strict_types=1);

namespace QuillStack\Mocks;

use Psr\Http\Message\UriInterface;
use QuillStack\DI\Container;
use QuillStack\Http\Uri\Factory\UriFactory;
use QuillStack\Http\Uri\Uri;

abstract class AbstractUriMock
{
    /**
     * @var Container
     */
    protected Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * @return Uri
     */
    public function get(): UriInterface
    {
        return $this->container->get(UriFactory::class)->createUri(static::URI);
    }
}
