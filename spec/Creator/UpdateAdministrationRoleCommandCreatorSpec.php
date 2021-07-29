<?php

declare(strict_types=1);

namespace spec\Titi60\SyliusRbacPlugin\Creator;

use PhpSpec\ObjectBehavior;
use Titi60\SyliusRbacPlugin\Command\UpdateAdministrationRole;
use Titi60\SyliusRbacPlugin\Creator\CommandCreatorInterface;
use Titi60\SyliusRbacPlugin\Normalizer\AdministrationRolePermissionNormalizerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class UpdateAdministrationRoleCommandCreatorSpec extends ObjectBehavior
{
    function let(
        AdministrationRolePermissionNormalizerInterface $administrationRolePermissionNormalizer
    ): void {
        $this->beConstructedWith($administrationRolePermissionNormalizer);
    }

    function it_implements_command_creator_interface(): void
    {
        $this->shouldImplement(CommandCreatorInterface::class);
    }

    function it_creates_update_administration_role_command_from_request(
        AdministrationRolePermissionNormalizerInterface $administrationRolePermissionNormalizer,
        Request $request
    ): void {
        $request->request = new ParameterBag([
            'administration_role_name' => 'Product Manager',
            'permissions' => [
                'catalog_management' => [
                    'read' => 'on',
                    'write' => 'on',
                ],
                'configuration' => [
                    'read' => 'on',
                ],
            ],
        ]);

        $request->attributes = new ParameterBag(['id' => 1]);

        $administrationRolePermissionNormalizer->normalize(
            [
                'catalog_management' => [
                    'read' => 'on',
                    'write' => 'on',
                ],
                'configuration' => [
                    'read' => 'on',
                ],
            ]
        )->willReturn(
            [
                'catalog_management' => ['read', 'write'],
                'configuration' => ['read'],
            ]
        );

        $payload = [
            'administration_role_id' => 1,
            'administration_role_name' => 'Product Manager',
            'permissions' => [
                'catalog_management' => ['read', 'write'],
                'configuration' => ['read'],
            ],
        ];

        $this->fromRequest($request)->shouldBeCommandWithPayload($payload);
    }

    public function getMatchers(): array
    {
        return [
            'beCommandWithPayload' => function (UpdateAdministrationRole $subject, array $payload): bool {
                return
                    $subject->administrationRoleId() === $payload['administration_role_id'] &&
                    $subject->administrationRoleName() === $payload['administration_role_name'] &&
                    $subject->permissions() === $payload['permissions']
                ;
            },
        ];
    }
}
