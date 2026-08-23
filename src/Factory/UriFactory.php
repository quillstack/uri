<?php

declare(strict_types=1);

namespace Quillstack\Uri\Factory;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Quillstack\Uri\Uri;
use Quillstack\Uri\Validator\UriHostValidator;
use Quillstack\Uri\Validator\UriSchemeValidator;

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
     * @var array<string, int>
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
     * Neither validator needs anything, so this makes its own where none was given.
     *
     * They used to be filled by the container alone, which meant `new UriFactory()` — the
     * obvious way to write it, and the way anybody reading PSR-17 would — got a factory whose
     * properties were never set, and died on the first URI it was handed. A component that
     * cannot be built without a container is one you have to have read the container's
     * documentation to use at all.
     */
    public function __construct(
        ?UriSchemeValidator $uriSchemeValidator = null,
        ?UriHostValidator $uriHostValidator = null
    ) {
        $this->uriSchemeValidator = $uriSchemeValidator ?? new UriSchemeValidator();
        $this->uriHostValidator = $uriHostValidator ?? new UriHostValidator();
    }

    /**
     * {@inheritDoc}
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $uri = self::withPath($uri);
        $slashArray = $this->getSlashArray($uri);
        $colonArray = $this->getColonArray($uri);

        $userInfoHostPort = $this->getUserInfoHostPort($slashArray);
        $scheme = $this->getScheme($colonArray);
        [$userInfo, $authority] = $this->getUserInfoAndAuthority($userInfoHostPort);
        [$host, $port] = $this->getHostAndPort($authority, $scheme);
        [$path, $queryWithFragment] = $this->getPathAndQueryWithFragment($slashArray);
        [$query, $fragment] = $this->getQueryAndFragment($queryWithFragment);

        return new Uri($scheme, $userInfo, $host, $port, $query, $path, $fragment);
    }

    /**
     * Puts the slash back where a URI was written without one.
     *
     * `https://example.org` is a URI with an empty path and every bit as valid as
     * `https://example.org/`, but this is taken apart by splitting on slashes and there were
     * only two — so the host could not be found and the whole thing was refused. The same
     * went for `https://example.org?a=1` and for anything ending at a fragment.
     */
    private static function withPath(string $uri): string
    {
        $mark = strpos($uri, self::SLASH_DELIMITER . self::SLASH_DELIMITER);

        if ($mark === false) {
            return $uri;
        }

        $after = $mark + 2;
        $rest = substr($uri, $after);

        // Where the authority runs to the end, or straight into a query or a fragment, there
        // is no path — which is written as the slash it means.
        $ends = strcspn($rest, self::SLASH_DELIMITER . self::QUERY_STRING_DELIMITER . self::FRAGMENT_DELIMITER);

        if (($rest[$ends] ?? '') === self::SLASH_DELIMITER) {
            return $uri;
        }

        return substr($uri, 0, $after + $ends) . self::SLASH_DELIMITER . substr($rest, $ends);
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
     * @return array{0: string, 1: int}
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
     * @param string[] $slashArray
     *
     * @return string[]
     */
    private function getPathAndQueryWithFragment(array $slashArray): array
    {
        // Rooted, because that is what it was in the string it came out of.
        $path = $slashArray[3] !== '' ? '/' . $this->implodePath($slashArray) : self::DEFAULT_PATH;
        $query = self::DEFAULT_QUERY;

        // The path ends at whichever comes first. Looking only for the question mark left a
        // fragment written without one — `https://example.org/#top` — stuck on the end of the
        // path, where it named a directory nobody has.
        $ends = strcspn($path, self::QUERY_STRING_DELIMITER . self::FRAGMENT_DELIMITER);

        if ($ends < strlen($path)) {
            $query = substr($path, $path[$ends] === self::QUERY_STRING_DELIMITER ? $ends + 1 : $ends);
            $path = substr($path, 0, $ends);
        }

        return [$path === '' ? self::DEFAULT_PATH : $path, $query];
    }

    /**
     * @param string[] $slashArray
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
     * @return array{0: string, 1: string}
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
     * @return string[]
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
     * @return string[]
     */
    private function getColonArray(string $uri): array
    {
        return explode(self::COLON_DELIMITER, $uri);
    }

    /**
     * @param string[] $colonArray
     *
     * @return string
     */
    private function getScheme(array $colonArray): string
    {
        return $colonArray[0];
    }

    /**
     * @param string[] $slashArray
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
     * @return string[]
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
     * @return string[]
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
