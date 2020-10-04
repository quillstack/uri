<?php

declare(strict_types=1);

namespace QuillStack\Http\Uri\Validator;

use QuillStack\Http\Uri\Factory\Exceptions\UnknownHostException;
use QuillStack\ValidatorInterface;

final class UriHostValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private array $slashArray;

    /**
     * @param array $slashArray
     *
     * @return UriHostValidator
     */
    public function setSlashArray(array $slashArray): self
    {
        $this->slashArray = $slashArray;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        if (!isset($this->slashArray[3])) {
            throw new UnknownHostException('Cannot determine the host from the URI');
        }

        return true;
    }
}
