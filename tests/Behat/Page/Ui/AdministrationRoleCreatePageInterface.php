<?php

declare(strict_types=1);

namespace Tests\Titi60\SyliusRbacPlugin\Behat\Page\Ui;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface;

interface AdministrationRoleCreatePageInterface extends CreatePageInterface
{
    public function nameIt(string $name): void;

    public function getNameValidationMessage(): string;
}
