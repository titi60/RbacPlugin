<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Cli\Granter;

interface AdministratorAccessGranterInterface
{
    public function __invoke(string $email, string $roleName, array $sections): void;
}
