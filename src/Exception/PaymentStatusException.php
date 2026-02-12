<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Exception;

use DomainException;

/**
 * Use when action requires a specific payment status that is not met.
 * Example: user tries to access premium feature without completed payment.
 *
 * Suggested HTTP mapping: 402 Payment Required.
 */
class PaymentStatusException extends \DomainException 
{
    
}
