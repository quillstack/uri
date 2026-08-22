<?php

declare(strict_types=1);

namespace Quillstack\Uri\Tests\Uri;

use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\Uri\Factory\UriFactory;

/**
 * The parts of PSR-7 which are easy to get subtly wrong, and which this package did.
 */
class PsrConformanceTest
{
    public function __construct(
        private UriFactory $uriFactory,
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        //
    }

    /**
     * Saying `https://example.com:443` says nothing `https://example.com` does not, so PSR-7
     * asks for nothing back where the port is the usual one for the scheme.
     */
    public function theUsualPortForTheSchemeIsNotAPort()
    {
        $this->assertBoolean->isTrue(
            $this->uriFactory->createUri('https://example.com:443/x')->getPort() === null
        );
        $this->assertBoolean->isTrue(
            $this->uriFactory->createUri('http://example.com:80/x')->getPort() === null
        );
        $this->assertEqual->equal(
            8443,
            $this->uriFactory->createUri('https://example.com:8443/x')->getPort()
        );
    }

    public function andItIsNotInTheAuthorityEither()
    {
        $this->assertEqual->equal(
            'example.com',
            $this->uriFactory->createUri('https://example.com:443/x')->getAuthority()
        );
        $this->assertEqual->equal(
            'https://example.com/x',
            (string) $this->uriFactory->createUri('https://example.com:443/x')
        );
    }

    /**
     * PSR-7 spells the authority `[user-info@]host[:port]`. It used to be the host and port
     * alone, so anything rebuilding a URI from its parts lost the credentials.
     */
    public function theAuthorityCarriesTheCredentials()
    {
        $this->assertEqual->equal(
            'user:secret@example.com:8443',
            $this->uriFactory->createUri('https://user:secret@example.com:8443/x')->getAuthority()
        );
    }

    /**
     * A path beside an authority is an absolute one. It came back without its leading slash,
     * and writing the URI out again then gave `//users/43` — which names a host called
     * `users`, and is a different request altogether.
     */
    public function aPathBesideAnAuthorityIsRooted()
    {
        $uri = $this->uriFactory->createUri('https://example.com/users/42');

        $this->assertEqual->equal('/users/42', $uri->getPath());
        $this->assertEqual->equal(
            'https://example.com/users/43',
            (string) $uri->withPath('/users/43')
        );
        $this->assertEqual->equal(
            'https://example.com/users/43',
            (string) $uri->withPath('users/43')
        );
    }

    /**
     * Writing one out gives back what went in.
     */
    public function whatGoesInComesBackOut()
    {
        foreach ([
            'https://user:secret@example.com:8443/users/42?page=2#top',
            'http://127.0.0.1/',
            'https://example.com/a/b/c?x=1',
        ] as $uri) {
            $this->assertEqual->equal($uri, (string) $this->uriFactory->createUri($uri));
        }
    }
}
