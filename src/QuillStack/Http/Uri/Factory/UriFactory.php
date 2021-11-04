<?php

declare(strict_types=1);

namespace QuillStack\Http\Uri\Factory;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use QuillStack\Http\Uri\Uri;
use QuillStack\Http\Uri\Validator\UriHostValidator;
use QuillStack\Http\Uri\Validator\UriSchemeValidator;

class UriFactory implements UriFactoryInterface
{
    /**
     * @var string
     */
    public const PORT_DELIMITER = ':';

    /**
     * @var string
     */
    public const USER_INFO_DELIMITER = '@';

    /**
     * @var string
     */
    public const QUERY_STRING_DELIMITER = '?';

    /**
     * @var string
     */
    public const FRAGMENT_DELIMITER = '#';

    /**
     * @var string
     */
    public const DEFAULT_QUERY = '';

    /**
     * @var string
     */
    public const DEFAULT_PATH = '/';

    /**
     * @var string
     */
    private const COLON_DELIMITER = ':';

    /**
     * @var string
     */
    private const SLASH_DELIMITER = '/';

    /**
     * @var array
     */
    private const SCHEME_PORTS = [
        Uri::SCHEME_HTTPS => Uri::DEFAULT_PORT_HTTPS,
        Uri::SCHEME_HTTP => Uri::DEFAULT_PORT_HTTP,
    ];

    /**
     * @var UriSchemeValidator
     */
    public UriSchemeValidator $uriSchemeValidator;

    /**
     * @var UriHostValidator
     */
    public UriHostValidator $uriHostValidator;

    /**
     * {@inheritDoc}
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $slashArray = $this->getSlashArray($uri);
        $colonArray = $this->getColonArray($uri);

        $userInfoHostPort = $this->getUserInfoHostPort($slashArray);
        $scheme = $this->getScheme($colonArray);
        [$userInfo, $authority] = $this->getUserInfoAndAuthority($userInfoHostPort);
        [$host, $port] = $this->getHostAndPort($authority, $scheme);
        [$path, $queryWithFragment] = $this->getPathAndQueryWithFragment($slashArray);
        [$query, $fragment] = $this->getQueryAndFragment($queryWithFragment);

        return new Uri($scheme, $authority, $userInfo, $host, $port, $query, $path, $fragment);
    }

    /**
     * @param string $userInfoHostPort
     *
     * @return string[]
     */
    private function getUserInfoAndAuthority(string $userInfoHostPort): array
    {
        $atArray = $this->getAtArray($userInfoHostPort);

        if ($atArray) {
            return $atArray;
        }

        return ['', $userInfoHostPort];
    }

    /**
     * @param string $userInfoHostPort
     * @param string $scheme
     *
     * @return array
     */
    private function getHostAndPort(string $userInfoHostPort, string $scheme): array
    {
        $hostArray = $this->getHostArray($userInfoHostPort);

        if ($hostArray) {
            return [
                $hostArray[0],
                (int) $hostArray[1],
            ];
        }

        return [
            $userInfoHostPort,
            $this->getPort($scheme),
        ];
    }

    /**
     * @param array $slashArray
     *
     * @return array
     */
    private function getPathAndQueryWithFragment(array $slashArray): array
    {
        $path = $slashArray[3] !== '' ? $this->implodePath($slashArray) : self::DEFAULT_PATH;
        $query = self::DEFAULT_QUERY;

        if (strstr($path, self::QUERY_STRING_DELIMITER)) {
            $pathArray = explode(self::QUERY_STRING_DELIMITER, $path);
            $path = $pathArray[0];
            $query = $pathArray[1];
        }

        return [$path, $query];
    }

    /**
     * @param array $slashArray
     *
     * @return string
     */
    private function implodePath(array $slashArray): string
    {
        return implode('/', array_slice($slashArray, 3));
    }

    /**
     * @param string $queryWithFragment
     *
     * @return array
     */
    private function getQueryAndFragment(string $queryWithFragment): array
    {
        $hashArray = explode(self::FRAGMENT_DELIMITER, $queryWithFragment);

        if (isset($hashArray[1])) {
            return $hashArray;
        }

        return [
            $queryWithFragment,
            '',
        ];
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    private function getSlashArray(string $uri): array
    {
        $slashArray = explode(self::SLASH_DELIMITER, $uri);
        $this->uriHostValidator->setSlashArray($slashArray)->validate();

        return $slashArray;
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    private function getColonArray(string $uri): array
    {
        return explode(self::COLON_DELIMITER, $uri);
    }

    /**
     * @param array $colonArray
     *
     * @return string
     */
    private function getScheme(array $colonArray): string
    {
        return $colonArray[0];
    }

    /**
     * @param array $slashArray
     *
     * @return string
     */
    private function getUserInfoHostPort(array $slashArray): string
    {
        return $slashArray[2];
    }

    /**
     * @param string $userInfoHostPort
     *
     * @return array
     */
    private function getAtArray(string $userInfoHostPort): array
    {
        if (!strstr($userInfoHostPort, self::USER_INFO_DELIMITER)) {
            return [];
        }

        return explode(self::USER_INFO_DELIMITER, $userInfoHostPort);
    }

    /**
     * @param string $host
     *
     * @return array
     */
    private function getHostArray(string $host): array
    {
        if (!strstr($host, self::PORT_DELIMITER)) {
            return [];
        }

        return explode(self::PORT_DELIMITER, $host);
    }

    /**
     * @param string $scheme
     *
     * @return int
     */
    private function getPort(string $scheme): int
    {
        $this->uriSchemeValidator->setScheme($scheme)->validate();

        return self::SCHEME_PORTS[$scheme];
    }
}
