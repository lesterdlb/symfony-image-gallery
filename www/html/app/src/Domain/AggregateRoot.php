<?php

declare(strict_types=1);

namespace App\Domain;

use Symfony\Contracts\EventDispatcher\Event;

abstract class AggregateRoot
{
    private array $domainEvents = [];
    private array $queueMessages = [];

    final public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final public function pullQueueMessages(): array
    {
        $queueMessages       = $this->queueMessages;
        $this->queueMessages = [];

        return $queueMessages;
    }

    final protected function record(Event $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    final protected function addMessage(QueueMessage $queueMessage): void
    {
        $this->queueMessages[] = $queueMessage;
    }
}