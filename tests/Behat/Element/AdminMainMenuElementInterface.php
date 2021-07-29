<?php

declare(strict_types=1);

namespace Tests\Titi60\SyliusRbacPlugin\Behat\Element;

interface AdminMainMenuElementInterface
{
    /** @return array|string[] */
    public function getAvailableSections(): array;
}
