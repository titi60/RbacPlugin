<?php

declare(strict_types=1);

namespace Tests\Titi60\SyliusRbacPlugin\Behat\Element;

interface AdministrationRolesElementInterface
{
    public function selectAdministrationRole(string $administrationRoleName): void;

    public function canRemoveAdministrationRole(): bool;
}
