<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Exception;

use DomainException;

/**
 * Use when user lacks required relationship or role to perform an action on the aggregate.
 * Example: non-owner tries to modify resource, user without admin role tries to delete.
 *
 * Suggested HTTP mapping: 403 Forbidden.
 */
class InsufficientPrivilegeException extends \DomainException
{
    
}
