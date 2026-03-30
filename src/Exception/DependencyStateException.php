<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Exception;

use DomainException;

/**
 * Use when action depends on another aggregate's state which is either unavailable or not in expected state.
 * Example: creating an invoice for an order that doesn't exist or isn't confirmed yet.
 *
 * Suggested HTTP mapping: 424 Failed Dependency.
 */
class DependencyStateException extends DomainException 
{
    
}
