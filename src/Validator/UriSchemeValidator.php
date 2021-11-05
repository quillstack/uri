<?php

declare(strict_types=1);

namespace Quillstack\Uri\Validator;

use Quillstack\Uri\Factory\Exceptions\UnknownSchemeException;
use Quillstack\Uri\Uri;
use Quillstack\ValidatorInterface\ValidatorInterface;

class UriSchemeValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private const AVAILABLE_SCHEMES = [
        Uri::SCHEME_HTTP,
        Uri::SCHEME_HTTPS,
    ];

    /**
     * @var string
     */
    private string $scheme;

    /**
     * @param string $scheme
     *
     * @return UriSchemeValidator
     */
    public function setScheme(string $scheme): self
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        if (!in_array($this->scheme, self::AVAILABLE_SCHEMES, true)) {
            throw new UnknownSchemeException("Scheme not known: {$this->scheme}");
        }

        return true;
    }
}
