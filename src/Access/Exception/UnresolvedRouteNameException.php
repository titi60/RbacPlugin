<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Access\Exception;

final class UnresolvedRouteNameException extends \InvalidArgumentException
{
    public static function withRouteName(string $routeName): self
    {
        return new self(sprintf('Route "%s" cannot be resolved to access request object', $routeName));
    }
}
