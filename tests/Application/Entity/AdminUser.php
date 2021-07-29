<?php

declare(strict_types=1);

namespace Tests\Titi60\SyliusRbacPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleAwareInterface;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleAwareTrait;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_admin_user")
 */
class AdminUser extends BaseAdminUser implements AdministrationRoleAwareInterface
{
    use AdministrationRoleAwareTrait;
}
