<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Fixture;

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Titi60\SyliusRbacPlugin\Access\Model\OperationType;
use Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Titi60\SyliusRbacPlugin\Model\Permission;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class AdministrationRoleFixture extends AbstractFixture implements FixtureInterface
{
    /** @var FactoryInterface */
    private $administrationRoleFactory;

    /** @var ObjectManager */
    private $administrationRoleManager;

    public function __construct(FactoryInterface $administrationRoleFactory, ObjectManager $administrationRoleManager)
    {
        $this->administrationRoleFactory = $administrationRoleFactory;
        $this->administrationRoleManager = $administrationRoleManager;
    }

    public function getName(): string
    {
        return 'administration_role';
    }

    public function load(array $options): void
    {
        /** @var AdministrationRoleInterface $administrationRole */
        $administrationRole = $this->administrationRoleFactory->createNew();

        $administrationRole->setName($options['name']);

        foreach ($options['permissions'] as $permissionName) {
            $administrationRole
                ->addPermission(Permission::ofType($permissionName, [OperationType::read(), OperationType::write()]))
            ;
        }

        $this->administrationRoleManager->persist($administrationRole);
        $this->administrationRoleManager->flush();
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->scalarNode('name')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('permissions')
                    ->scalarPrototype()->defaultValue([])
                ->end()
            ->end()
        ->end();
    }
}
