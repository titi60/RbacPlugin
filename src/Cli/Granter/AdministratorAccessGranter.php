<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Cli\Granter;

use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Titi60\SyliusRbacPlugin\Access\Model\OperationType;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRole;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleAwareInterface;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Titi60\SyliusRbacPlugin\Model\Permission;

final class AdministratorAccessGranter implements AdministratorAccessGranterInterface
{
    /** @var RepositoryInterface */
    private $administratorRepository;

    /** @var RepositoryInterface */
    private $administratorRoleRepository;

    /** @var ObjectManager */
    private $objectManager;

    public function __construct(
        RepositoryInterface $administratorRepository,
        RepositoryInterface $administratorRoleRepository,
        ObjectManager $objectManager
    ) {
        $this->administratorRepository = $administratorRepository;
        $this->administratorRoleRepository = $administratorRoleRepository;
        $this->objectManager = $objectManager;
    }

    public function __invoke(string $email, string $roleName, array $sections): void
    {
        /** @var AdministrationRoleAwareInterface|null $admin */
        $admin = $this->administratorRepository->findOneBy(['email' => $email]);

        if (null === $admin) {
            throw new \InvalidArgumentException(
                sprintf('Administrator with email address %s does not exist. Aborting.', $email))
            ;
        }

        $administrationRole = $this->getOrCreateAdministrationRole($roleName);

        foreach ($sections as $section) {
            $administrationRole->addPermission(
                Permission::ofType(
                    $section,
                    [OperationType::read(), OperationType::write()])
            );
        }

        $this->administratorRoleRepository->add($administrationRole);
        $admin->setAdministrationRole($administrationRole);

        $this->objectManager->flush();
    }

    private function getOrCreateAdministrationRole(string $roleName): AdministrationRoleInterface
    {
        // This behaviour is debatable - either just override the selected role or throw an exception.
        /** @var AdministrationRoleInterface|null $administrationRole */
        $administrationRole = $this->administratorRoleRepository->findOneBy(['name' => $roleName]);

        if (null === $administrationRole) {
            $administrationRole = new AdministrationRole();
            $administrationRole->setName($roleName);
        }

        return $administrationRole;
    }
}
