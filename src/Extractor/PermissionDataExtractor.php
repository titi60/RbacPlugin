<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Extractor;

use Titi60\SyliusRbacPlugin\Model\PermissionInterface;

final class PermissionDataExtractor implements PermissionDataExtractorInterface
{
    public function extract(array $permissions): array
    {
        $permissionTypes = [];

        /** @var PermissionInterface $permission */
        foreach ($permissions as $permission) {
            $permissionTypes[] = $permission->type();
        }

        return $permissionTypes;
    }
}
