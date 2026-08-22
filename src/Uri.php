<?php

declare(strict_types=1);

namespace Quillstack\Uri;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    /**
     * @var int
     */
    public const DEFAULT_PORT_HTTP = 80;

    /**
     * @var int
     */
    public const DEFAULT_PORT_HTTPS = 443;

    /**
     * @var string
     */
    public const SCHEME_HTTP = 'http';

    /**
     * @var string
     */
    public const SCHEME_HTTPS = 'https';

    /**
     * @var int[]
     */
    public const DEFAULT_PORTS = [
        self::DEFAULT_PORT_HTTP,
        self::DEFAULT_PORT_HTTPS,
    ];

    /**
     * The authority is not one of these: it is `[user-info@]host[:port]`, so keeping a copy
     * beside the three parts it is made of is a second answer waiting to disagree with the
     * first.
     */
    public function __construct(
        private string $scheme = '',
        private string $userInfo = '',
        private string $host = '',
        private ?int $port = null,
        private string $query = '',
        private string $path = '',
        private string $fragment = ''
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthority()
    {
        // PSR-7 spells this `[user-info@]host[:port]`, and says a port which is the standard
        // one for the scheme does not belong in it. It used to be the host and port alone,
        // so anything rebuilding a URI from its parts lost the credentials.
        if ($this->host === '') {
            return '';
        }

        $authority = $this->userInfo === '' ? '' : "{$this->userInfo}@";
        $authority .= $this->host;
        $port = $this->getPort();

        return $port === null ? $authority : "{$authority}:{$port}";
    }

    /**
     * {@inheritDoc}
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * {@inheritDoc}
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritDoc}
     */
    public function getPort()
    {
        // Saying `https://example.com:443` says nothing `https://example.com` does not, so
        // PSR-7 asks for nothing back where the port is the usual one for the scheme.
        $standard = match ($this->scheme) {
            self::SCHEME_HTTP => self::DEFAULT_PORT_HTTP,
            self::SCHEME_HTTPS => self::DEFAULT_PORT_HTTPS,
            default => null,
        };

        return $this->port === $standard ? null : $this->port;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritDoc}
     */
    public function withScheme($scheme)
    {
        $new = clone $this;
        $new->scheme = $scheme;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withUserInfo($user, $password = null)
    {
        $new = clone $this;
        $new->userInfo = $password ? "{$user}:{$password}" : $user;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withHost($host)
    {
        $new = clone $this;
        $new->host = $host;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withPort($port)
    {
        $new = clone $this;
        $new->port = $port;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withPath($path)
    {
        $new = clone $this;
        $new->path = $path;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withQuery($query)
    {
        $new = clone $this;
        $new->query = $query;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withFragment($fragment)
    {
        $new = clone $this;
        $new->fragment = $fragment;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $uri = "{$this->scheme}://";
        $authority = $this->getAuthority();

        $uri .= $authority !== '' ? $authority : $this->host;

        // A path beside an authority has to be rooted, whether or not the one it was given
        // was: `//users/43` names a host called `users`, which is a different request.
        if ($this->path !== '') {
            $uri .= str_starts_with($this->path, '/') ? $this->path : '/' . $this->path;
        } else {
            $uri .= '/';
        }

        if ($this->query) {
            $uri .= "?{$this->query}";
        }

        if ($this->fragment) {
            $uri .= "#{$this->fragment}";
        }

        return $uri;
    }
}
