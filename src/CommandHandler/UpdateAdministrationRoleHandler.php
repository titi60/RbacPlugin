<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\CommandHandler;

use Doctrine\Persistence\ObjectManager;
use Titi60\SyliusRbacPlugin\Message\UpdateAdministrationRole;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Titi60\SyliusRbacPlugin\Factory\AdministrationRoleFactoryInterface;
use Titi60\SyliusRbacPlugin\Model\PermissionInterface;
use Titi60\SyliusRbacPlugin\Validator\AdministrationRoleValidatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateAdministrationRoleHandler implements MessageHandlerInterface
{
    /** @var ObjectManager */
    private $administrationRoleManager;

    /** @var AdministrationRoleFactoryInterface */
    private $administrationRoleFactory;

    /** @var RepositoryInterface */
    private $administrationRoleRepository;

    /** @var AdministrationRoleValidatorInterface */
    private $validator;

    /** @var string */
    private $validationGroup;

    public function __construct(
        ObjectManager $administrationRoleManager,
        AdministrationRoleFactoryInterface $administrationRoleFactory,
        RepositoryInterface $administrationRoleRepository,
        AdministrationRoleValidatorInterface $validator,
        string $validationGroup
    ) {
        $this->administrationRoleManager = $administrationRoleManager;
        $this->administrationRoleFactory = $administrationRoleFactory;
        $this->administrationRoleRepository = $administrationRoleRepository;
        $this->validator = $validator;
        $this->validationGroup = $validationGroup;
    }

    public function __invoke(UpdateAdministrationRole $message): void
    {
        /** @var AdministrationRoleInterface|null $administrationRole */
        $administrationRole = $this
            ->administrationRoleRepository
            ->find($message->administrationRoleId())
        ;

        if (null === $administrationRole) {
            throw new \InvalidArgumentException('sylius_rbac.administration_role_does_not_exist');
        }

        $administrationRoleUpdates = $this->administrationRoleFactory->createWithNameAndPermissions(
            $message->administrationRoleName(),
            $message->permissions()
        );

        $this->validator->validate($administrationRoleUpdates, $this->validationGroup);

        $administrationRole->setName($administrationRoleUpdates->getName());
        $administrationRole->clearPermissions();

        /** @var PermissionInterface $permission */
        foreach ($administrationRoleUpdates->getPermissions() as $permission) {
            $administrationRole->addPermission($permission);
        }

        $this->administrationRoleManager->flush();
    }
}
