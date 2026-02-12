<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Exception;

use DomainException;

/**
 * Use when input data violates business rules (e.g., invalid format, out of range values).
 * For logically invalid arguments (type errors, null where not allowed), use InvalidArgumentException instead.
 *
 * Suggested HTTP mapping: 400 Bad Request.
 */
class InvalidInputException extends \DomainException 
{
    
}
