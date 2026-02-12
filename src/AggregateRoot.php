<?php

declare(strict_types=1);

namespace PhpArchitecture\DomainCore;

abstract class AggregateRoot
{
    /** @var DomainEvent[] */
    private array $events = [];

    /**
     * @return DomainEvent[]
     */
    final public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @return DomainEvent[]
     */
    final public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    final protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
