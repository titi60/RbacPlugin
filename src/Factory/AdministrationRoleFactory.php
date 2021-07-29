<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Titi60\SyliusRbacPlugin\Model\Permission;

final class AdministrationRoleFactory implements AdministrationRoleFactoryInterface
{
    /** @var FactoryInterface */
    private $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createWithNameAndPermissions(string $name, array $permissions): AdministrationRoleInterface
    {
        /** @var AdministrationRoleInterface $administrationRole */
        $administrationRole = $this->decoratedFactory->createNew();

        $administrationRole->setName($name);

        foreach ($permissions as $permission => $operationTypes) {
            $administrationRole->addPermission(Permission::ofType($permission, $operationTypes));
        }

        return $administrationRole;
    }

    public function createNew(): AdministrationRoleInterface
    {
        /** @var AdministrationRoleInterface $administrationRole */
        $administrationRole = $this->decoratedFactory->createNew();

        return $administrationRole;
    }
}
