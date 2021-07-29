<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Access\Creator;

use Titi60\SyliusRbacPlugin\Access\Exception\UnresolvedRouteNameException;
use Titi60\SyliusRbacPlugin\Access\Model\AccessRequest;

interface AccessRequestCreatorInterface
{
    /** @throws UnresolvedRouteNameException */
    public function createFromRouteName(string $routeName, string $requestMethod): AccessRequest;
}
