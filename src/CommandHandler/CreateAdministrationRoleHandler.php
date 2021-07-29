<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\CommandHandler;

use Doctrine\Persistence\ObjectManager;
use Titi60\SyliusRbacPlugin\Factory\AdministrationRoleFactoryInterface;
use Titi60\SyliusRbacPlugin\Message\CreateAdministrationRole;
use Titi60\SyliusRbacPlugin\Validator\AdministrationRoleValidatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateAdministrationRoleHandler implements MessageHandlerInterface
{
    /** @var ObjectManager */
    private $administrationRoleManager;

    /** @var AdministrationRoleFactoryInterface */
    private $administrationRoleFactory;

    /** @var AdministrationRoleValidatorInterface */
    private $validator;

    /** @var string */
    private $validationGroup;

    public function __construct(
        ObjectManager $objectManager,
        AdministrationRoleFactoryInterface $administrationRoleFactory,
        AdministrationRoleValidatorInterface $validator,
        string $validationGroup
    ) {
        $this->administrationRoleManager = $objectManager;
        $this->administrationRoleFactory = $administrationRoleFactory;
        $this->validator = $validator;
        $this->validationGroup = $validationGroup;
    }

    public function __invoke(CreateAdministrationRole $message): void
    {
        $administrationRole = $this->administrationRoleFactory->createWithNameAndPermissions(
            $message->administrationRoleName(),
            $message->permissions()
        );

        $this->validator->validate($administrationRole, $this->validationGroup);

        $this->administrationRoleManager->persist($administrationRole);
        $this->administrationRoleManager->flush();
    }
}
