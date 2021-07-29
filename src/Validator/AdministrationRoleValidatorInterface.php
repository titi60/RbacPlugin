<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Validator;

use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface;

interface AdministrationRoleValidatorInterface
{
    public function validate(AdministrationRoleInterface $administrationRole, string $validationGroup): void;
}
