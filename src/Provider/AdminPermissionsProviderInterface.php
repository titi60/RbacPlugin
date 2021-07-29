<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Provider;

interface AdminPermissionsProviderInterface
{
    /** @return array|string[] */
    public function getPossiblePermissions(): array;
}
