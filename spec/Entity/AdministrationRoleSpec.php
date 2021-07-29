<?php

declare(strict_types=1);

namespace spec\Titi60\SyliusRbacPlugin\Entity;

use PhpSpec\ObjectBehavior;
use Titi60\SyliusRbacPlugin\Access\Model\OperationType;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Titi60\SyliusRbacPlugin\Model\Permission;

final class AdministrationRoleSpec extends ObjectBehavior
{
    function it_implements_administration_role_interface(): void
    {
        $this->shouldImplement(AdministrationRoleInterface::class);
    }

    function it_has_name(): void
    {
        $this->setName('Root');
        $this->getName()->shouldReturn('Root');
    }

    function it_has_permissions(): void
    {
        $this->addPermission(Permission::catalogManagement([OperationType::read()]));
        $this->addPermission(Permission::customerManagement([OperationType::read(), OperationType::write()]));

        $this->hasPermission(Permission::catalogManagement([OperationType::read()]))->shouldReturn(true);
        $this
            ->hasPermission(Permission::customerManagement([OperationType::read(), OperationType::write()]))
            ->shouldReturn(true)
        ;

        $this->removePermission(Permission::customerManagement());
        $this->hasPermission(Permission::customerManagement())->shouldReturn(false);
    }
}
