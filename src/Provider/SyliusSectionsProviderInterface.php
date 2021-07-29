<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Provider;

interface SyliusSectionsProviderInterface
{
    public function __invoke(): array;
}
