<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Access\Checker;

interface RouteNameCheckerInterface
{
    public function isAdminRoute(string $routeName): bool;
}
