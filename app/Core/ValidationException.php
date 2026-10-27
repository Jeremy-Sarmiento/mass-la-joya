<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Errores de validación con lista de campos.
 */
final class ValidationException extends RuntimeException
{
    /** @var array<string, string> */
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Los datos enviados no son válidos.');
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }
}
