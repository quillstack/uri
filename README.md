# Quillstack URI

[![Tests](https://github.com/quillstack/uri/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/uri/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/uri.svg)](https://packagist.org/packages/quillstack/uri)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/uri.svg)](https://packagist.org/packages/quillstack/uri)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/uri)](https://packagist.org/packages/quillstack/uri)
[![StyleCI](https://github.styleci.io/repos/301097623/shield?branch=main)](https://github.styleci.io/repos/301097623?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/uri/badge)](https://www.codefactor.io/repository/github/quillstack/uri)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=quillstack_uri&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_uri)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_uri&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_uri)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_uri&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_uri)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_uri&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_uri)
[![Security](https://sonarcloud.io/api/project_badges/measure?project=quillstack_uri&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_uri)
[![Maintainability](https://api.codeclimate.com/v1/badges/ff38df5bd11d8617e887/maintainability)](https://codeclimate.com/github/quillstack/uri/maintainability)
[![License](https://img.shields.io/packagist/l/quillstack/uri)](https://github.com/quillstack/uri/blob/main/LICENSE)

The simple implementation of [PSR-7: Uri](https://www.php-fig.org/psr/psr-7/). Full
documentation: https://quillstack.org/uri

A URI taken apart into the pieces an application asks about: which scheme, which host, which
path, what came after the question mark. Immutable, so handing one to something else cannot
change the one you kept — every `with…()` gives back a copy.

The factory needs nothing to be built:

```php
$factory = new UriFactory();
```

A container can build it instead, and will fill the same two validators; neither way is the one
you have to use.

## Why this exists

A URI is not a string, and the difference matters the moment anything acts on one. `getPort()`
has to return nothing where the port is the standard one for the scheme, because
`https://example.org:443` and `https://example.org` are the same address and an application
comparing them is comparing the same thing. The authority is `[user-info@]host[:port]`, so
rebuilding a URI from its parts without the credentials silently drops them.

Those are not opinions — PSR-7 says so, and this package got all three wrong until its
conformance was tested rather than assumed. **What is here is the specification, checked**: a
suite that reads the standard clause by clause, which is the reason to use a small
implementation rather than a hand-rolled `parse_url()`.

## Requirements

- PHP 8.1 or newer

## Installation

```shell
composer require quillstack/uri
```

## Usage

### Reading one apart

The factory asks for its validators, so the container builds it:

```php
use Quillstack\DI\Container;
use Quillstack\Uri\Factory\UriFactory;

$factory = (new Container())->get(UriFactory::class);
$uri = $factory->createUri('https://user:secret@example.com:8443/users/42?page=2#top');

$uri->getScheme();      // 'https'
$uri->getHost();        // 'example.com'
$uri->getPort();        // 8443
$uri->getPath();        // '/users/42'
$uri->getQuery();       // 'page=2'
$uri->getFragment();    // 'top'
$uri->getUserInfo();    // 'user:secret'
$uri->getAuthority();   // 'user:secret@example.com:8443'
(string) $uri;          // what went in, unchanged
```

A port which is the usual one for the scheme is not part of the authority, because saying
`https://example.com:443` says nothing `https://example.com` does not:

```php
$uri = $factory->createUri('https://example.com:443/x');

$uri->getPort();        // null
$uri->getAuthority();   // 'example.com'
(string) $uri;          // https://example.com/x
```

### A URI which stops at the host

All of these are URIs, and all of them used to be refused outright:

```php
$factory->createUri('https://example.org');        // path '/', nothing else
$factory->createUri('https://example.org?a=1');    // query 'a=1'
$factory->createUri('https://example.org#top');    // fragment 'top'
```

The path ends at whichever of `?` and `#` comes first, so a fragment written without a query
is a fragment rather than the end of a directory name.

### Changing one

Every change gives back a copy, so the one you were given stays as it was:

```php
$next = $uri->withPath('/users/43')->withQuery('page=3');

(string) $uri;     // https://example.com/users/42?page=2
(string) $next;    // https://example.com/users/43?page=3
```

### Routing on the path

This is what [quillstack/router](https://github.com/quillstack/router) dispatches on — the
path rather than the whole thing, so a query string does not turn a known route into a 404:

```php
$path = $request->getUri()->getPath();
```

## Technical documentation

`Uri` implements `Psr\Http\Message\UriInterface` in full: `getScheme()`, `getAuthority()`,
`getUserInfo()`, `getHost()`, `getPort()`, `getPath()`, `getQuery()`, `getFragment()`, the
matching `with…()` methods, and `__toString()`.

| Constant | Value |
| --- | --- |
| `Uri::SCHEME_HTTP` / `Uri::SCHEME_HTTPS` | `http` / `https` |
| `Uri::DEFAULT_PORT_HTTP` / `Uri::DEFAULT_PORT_HTTPS` | `80` / `443` |
| `Uri::DEFAULT_PORTS` | the two above, keyed by scheme |

`UriFactory` implements `Psr\Http\Message\UriFactoryInterface`; `createUri()` takes the string
apart and validates it.

| Exception | Thrown when |
| --- | --- |
| `UnknownSchemeException` | the scheme is not one this package knows |
| `UnknownHostException` | the host is not a host |

Both extend `UriException`, and both implement the validation interface from
[quillstack/validator-interface](https://github.com/quillstack/validator-interface).

## Benchmark

Measured with [quillstack/benchmark](https://github.com/quillstack/benchmark) on one address
carrying every part a URI can have — scheme, credentials, host, port, path, query and fragment.
All four take it apart identically. Runs are interleaved, each figure is the median of five, and
PHP is 8.5.7.

| | Version |
| --- | --- |
| quillstack/uri | v0.7.0 |
| nyholm/psr7 | 1.8.2 |
| laminas/laminas-diactoros | 3.8.0 |
| guzzlehttp/psr7 | 2.13.0 |

| | Per URI | Relative | Files loaded | Memory |
| --- | --- | --- | --- | --- |
| nyholm/psr7 | 4.1 µs | 0.65× | 9 | 71 kB |
| **quillstack/uri** | **6.3 µs** | — | 7 | 72 kB |
| laminas/laminas-diactoros | 13.1 µs | 2.1× | 2 | 66 kB |
| guzzlehttp/psr7 | 17.8 µs | 2.8× | 4 | 181 kB |

**`nyholm/psr7` takes a URI apart faster than this does**, by about half again, and it is worth
saying plainly: if parsing addresses in a loop is the thing your application does, that is the
one to use. At six microseconds each, a request that builds a dozen URIs spends seventy-five
microseconds on it, which is not where a request goes.

What this offers instead is the conformance suite — the three PSR-7 clauses above were each
wrong here once, and are each a test now.

## Tests

```shell
composer test
```

## The rest of Quillstack

This is one component of [Quillstack](https://github.com/quillstack), a PHP framework which is
as simple to use as it is strict about what it does.

- [quillstack/server-request](https://github.com/quillstack/server-request) — where a URI arrives from
- [quillstack/http-client](https://github.com/quillstack/http-client) — where one is sent to
- [quillstack/router](https://github.com/quillstack/router) — what matches on the path
- [quillstack/stream](https://github.com/quillstack/stream) — the other half of a PSR-7 message

## License

MIT. See [LICENSE](LICENSE).
