<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore\Identity;

interface AggregateId
{
    public function toString(): string;
}
