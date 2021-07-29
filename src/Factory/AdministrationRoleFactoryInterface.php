<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Factory;

use Sylius\Component\Resource\Factory\TranslatableFactoryInterface;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface;

interface AdministrationRoleFactoryInterface extends TranslatableFactoryInterface
{
    public function createWithNameAndPermissions(string $name, array $permissions): AdministrationRoleInterface;
}
