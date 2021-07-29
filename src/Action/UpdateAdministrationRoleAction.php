<?php

declare(strict_types=1);

namespace Titi60\SyliusRbacPlugin\Action;

use Titi60\SyliusRbacPlugin\Message\UpdateAdministrationRole;
use Titi60\SyliusRbacPlugin\Normalizer\AdministrationRolePermissionNormalizerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UpdateAdministrationRoleAction
{
    /** @var MessageBusInterface */
    private $bus;

    /** @var AdministrationRolePermissionNormalizerInterface */
    private $administrationRolePermissionNormalizer;

    /** @var Session */
    private $session;

    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(
        MessageBusInterface $bus,
        AdministrationRolePermissionNormalizerInterface $administrationRolePermissionNormalizer,
        Session $session,
        UrlGeneratorInterface $router
    ) {
        $this->bus = $bus;
        $this->administrationRolePermissionNormalizer = $administrationRolePermissionNormalizer;
        $this->session = $session;
        $this->router = $router;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $normalizedPermissions = $this
                ->administrationRolePermissionNormalizer
                ->normalize($request->request->get('permissions', []))
            ;

            $this->bus->dispatch(new UpdateAdministrationRole(
                $request->attributes->getInt('id'),
                $request->request->get('administration_role_name'),
                $normalizedPermissions
            ));

            $this->session->getFlashBag()->add(
                'success',
                'sylius_rbac.administration_role_successfully_updated'
            );
        } catch (\Exception $exception) {
            $this->session->getFlashBag()->add('error', $exception->getMessage());
        }

        return new RedirectResponse(
            $this->router->generate(
                'sylius_rbac_admin_administration_role_update_view', ['id' => $request->attributes->get('id')]
            )
        );
    }
}
