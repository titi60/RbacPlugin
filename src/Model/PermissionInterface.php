<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Model;

use Titi60\SyliusRbacPlugin\Access\Model\OperationType;

interface PermissionInterface
{
    public function operationTypes(): ?array;

    public function addOperationType(OperationType $operationType): void;

    public function type(): string;

    public function serialize(): string;
}
