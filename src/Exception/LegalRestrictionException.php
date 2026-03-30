<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Exception;

use DomainException;

/**
 * Use when action cannot be performed due to legal restrictions.
 * Example: content unavailable in certain regions, GDPR-restricted data access, age-restricted content.
 *
 * Suggested HTTP mapping: 451 Unavailable For Legal Reasons.
 */
class LegalRestrictionException extends DomainException 
{
    
}
