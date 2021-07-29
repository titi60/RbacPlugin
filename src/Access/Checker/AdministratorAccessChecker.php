<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Access\Checker;

use Sylius\Component\Core\Model\AdminUserInterface;
use Titi60\SyliusRbacPlugin\Access\Model\AccessRequest;
use Titi60\SyliusRbacPlugin\Access\Model\OperationType;
use Titi60\SyliusRbacPlugin\Access\Model\Section;
use Titi60\SyliusRbacPlugin\Model\Permission;
use Webmozart\Assert\Assert;

final class AdministratorAccessChecker implements AdministratorAccessCheckerInterface
{
    public function canAccessSection(AdminUserInterface $admin, AccessRequest $accessRequest): bool
    {
        $administrationRole = $admin->getAdministrationRole();
        Assert::notNull($administrationRole);

        /** @var Permission $permission */
        foreach ($administrationRole->getPermissions() as $permission) {
            if ($this->getSectionForPermission($permission)->equals($accessRequest->section())) {
                if (OperationType::READ === $accessRequest->operationType()->__toString()) {
                    return true;
                }

                return $this->canWriteAccess($permission);
            }
        }

        return false;
    }

    private function getSectionForPermission(Permission $permission): Section
    {
        switch (true) {
            case $permission->equals(Permission::configuration()):
                return Section::configuration();
            case $permission->equals(Permission::catalogManagement()):
                return Section::catalog();
            case $permission->equals(Permission::marketingManagement()):
                return Section::marketing();
            case $permission->equals(Permission::customerManagement()):
                return Section::customers();
            case $permission->equals(Permission::salesManagement()):
                return Section::sales();
        }

        return Section::ofType($permission->type());
    }

    private function canWriteAccess(Permission $permission): bool
    {
        /** @var OperationType $operationType */
        foreach ($permission->operationTypes() as $operationType) {
            if (OperationType::WRITE === $operationType->__toString()) {
                return true;
            }
        }

        return false;
    }
}
