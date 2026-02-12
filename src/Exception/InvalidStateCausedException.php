<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Exception;

use DomainException;

/**
 * Use when the provided data would cause the aggregate to enter an invalid state.
 * The repeated action can succeed after correcting the input data.
 *
 * Suggested HTTP mapping: 422 Unprocessable Entity.
 */
class InvalidStateCausedException extends \DomainException 
{
    
}
