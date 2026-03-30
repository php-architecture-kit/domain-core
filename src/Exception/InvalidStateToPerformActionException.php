<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Exception;

use DomainException;

/**
 * Use when aggregate's current state does not allow performing the requested action.
 * Example: trying to cancel an already cancelled order, or publish an already published article.
 *
 * Suggested HTTP mapping: 409 Conflict.
 */
class InvalidStateToPerformActionException extends DomainException 
{
    
}
