<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class UniqueConstraintException extends AbstractException
{
    public const UNIQUE_CONSTRAINT_ERROR_CODE = 'UNIQUE_CONSTRAINT_ERROR';

    public function __construct(string $message, string $errorCode = self::UNIQUE_CONSTRAINT_ERROR_CODE, array $errors = [])
    {
        parent::__construct($message, $errorCode, null, Response::HTTP_BAD_REQUEST);
    }
}
