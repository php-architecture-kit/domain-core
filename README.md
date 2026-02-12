# php-architecture-kit/domain-core

Domain-Driven Design building blocks for PHP applications. Provides abstract classes, interfaces, and categorized domain exceptions mappable to HTTP status codes.

## Features

- **AggregateRoot** - Base class with domain event recording
- **DomainEvent** - Marker interface for domain events
- **Categorized Exceptions** - 7 domain exceptions mapped to HTTP codes (400, 402, 403, 409, 422, 424, 451)
- **Zero dependencies** - Pure PHP, no external packages
- **PHP 7.4+** - Compatible with legacy and modern PHP

## Installation

```bash
composer require php-architecture-kit/domain-core
```

## Quick Start

### Aggregate Root

```php
use PhpArchitecture\DomainCore\AggregateRoot;
use PhpArchitecture\DomainCore\DomainEvent;

class OrderCreated implements DomainEvent
{
    public function __construct(
        public readonly string $orderId,
        public readonly array $items,
    ) {}
}

class Order extends AggregateRoot
{
    private string $id;
    private array $items;
    private string $status = 'draft';

    public static function create(string $id, array $items): self
    {
        $order = new self();
        $order->id = $id;
        $order->items = $items;
        $order->recordEvent(new OrderCreated($id, $items));

        return $order;
    }

    public function confirm(): void
    {
        if ($this->status !== 'draft') {
            throw new InvalidStateToPerformActionException('Order already confirmed');
        }
        
        $this->status = 'confirmed';
        $this->recordEvent(new OrderConfirmed($this->id));
    }
}
```

### Using Domain Events

```php
// Create aggregate and perform actions
$order = Order::create('order-123', ['item1', 'item2']);
$order->confirm();

// Get recorded events (without clearing)
$events = $order->getEvents(); // [OrderCreated, OrderConfirmed]

// Release events (get and clear)
$events = $order->releaseEvents(); // [OrderCreated, OrderConfirmed]
$events = $order->getEvents();     // []
```

## Domain Exceptions

All exceptions extend `\DomainException` and can be mapped to HTTP status codes in your infrastructure layer.

| Exception | HTTP Code | When to Use |
|-----------|-----------|-------------|
| `InvalidInputException` | 400 Bad Request | Input data violates business rules |
| `PaymentStatusException` | 402 Payment Required | Action requires specific payment status |
| `InsufficientPrivilegeException` | 403 Forbidden | User lacks required role/relationship |
| `InvalidStateToPerformActionException` | 409 Conflict | Aggregate state doesn't allow action |
| `InvalidStateCausedException` | 422 Unprocessable Entity | Data would cause invalid state |
| `DependencyStateException` | 424 Failed Dependency | Dependent aggregate unavailable/wrong state |
| `LegalRestrictionException` | 451 Unavailable For Legal Reasons | Legal restrictions prevent action |

### Exception Usage Examples

```php
use PhpArchitecture\DomainCore\Exception\InvalidInputException;
use PhpArchitecture\DomainCore\Exception\InsufficientPrivilegeException;
use PhpArchitecture\DomainCore\Exception\InvalidStateToPerformActionException;

class Order extends AggregateRoot
{
    public function addItem(string $sku, int $quantity, string $actorId): void
    {
        // 400 - Invalid input
        if ($quantity <= 0) {
            throw new InvalidInputException('Quantity must be positive');
        }

        // 403 - Insufficient privilege
        if ($this->ownerId !== $actorId) {
            throw new InsufficientPrivilegeException('Only owner can modify order');
        }

        // 409 - Invalid state to perform action
        if ($this->status === 'shipped') {
            throw new InvalidStateToPerformActionException('Cannot modify shipped order');
        }

        $this->items[] = new OrderItem($sku, $quantity);
    }
}
```

### HTTP Mapping (Infrastructure Layer)

```php
// Symfony Exception Listener
use PhpArchitecture\DomainCore\Exception\InvalidInputException;
use PhpArchitecture\DomainCore\Exception\PaymentStatusException;
use PhpArchitecture\DomainCore\Exception\InsufficientPrivilegeException;
use PhpArchitecture\DomainCore\Exception\InvalidStateToPerformActionException;
use PhpArchitecture\DomainCore\Exception\InvalidStateCausedException;
use PhpArchitecture\DomainCore\Exception\DependencyStateException;
use PhpArchitecture\DomainCore\Exception\LegalRestrictionException;

class DomainExceptionListener
{
    private const HTTP_MAP = [
        InvalidInputException::class => 400,
        PaymentStatusException::class => 402,
        InsufficientPrivilegeException::class => 403,
        InvalidStateToPerformActionException::class => 409,
        InvalidStateCausedException::class => 422,
        DependencyStateException::class => 424,
        LegalRestrictionException::class => 451,
    ];

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        foreach (self::HTTP_MAP as $class => $code) {
            if ($exception instanceof $class) {
                $event->setResponse(new JsonResponse(
                    ['error' => $exception->getMessage()],
                    $code
                ));
                return;
            }
        }
    }
}
```

## API Reference

### AggregateRoot

| Method | Visibility | Description |
|--------|------------|-------------|
| `getEvents(): DomainEvent[]` | public | Returns recorded events without clearing |
| `releaseEvents(): DomainEvent[]` | public | Returns and clears recorded events |
| `recordEvent(DomainEvent $event): void` | protected | Records a domain event |

### DomainEvent

Marker interface - implement in your domain event classes:

```php
interface DomainEvent {}
```

### Exceptions

All exceptions extend `\DomainException` and accept standard parameters:

```php
__construct(string $message = '', int $code = 0, ?Throwable $previous = null)
```

## Testing

Package is tested with PHPUnit in the [php-architecture-kit/workspace](https://github.com/php-architecture-kit/workspace) project.

## License

MIT