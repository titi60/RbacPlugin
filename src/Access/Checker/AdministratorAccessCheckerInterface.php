<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Access\Checker;

use Sylius\Component\Core\Model\AdminUserInterface;
use Titi60\SyliusRbacPlugin\Access\Model\AccessRequest;

interface AdministratorAccessCheckerInterface
{
    public function canAccessSection(AdminUserInterface $admin, AccessRequest $accessRequest): bool;
}
