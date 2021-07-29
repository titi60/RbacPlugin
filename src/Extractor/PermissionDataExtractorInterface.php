<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Extractor;

interface PermissionDataExtractorInterface
{
    public function extract(array $permissions): array;
}
