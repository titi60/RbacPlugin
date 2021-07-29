<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Normalizer;

interface AdministrationRolePermissionNormalizerInterface
{
    public function normalize(array $administrationRolePermissions): array;
}
