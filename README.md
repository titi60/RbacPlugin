<p align="center">
    <a href="https://odiseo.com.ar/" target="_blank" title="Odiseo">
        <img src="https://odiseo.io/assets/app/images/logo.png" alt="Sylius Rbac Plugin" />
    </a>
    <br />
    <a href="https://packagist.org/packages/odiseoteam/sylius-rbac-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/odiseoteam/sylius-rbac-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-rbac-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/odiseoteam/sylius-rbac-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/odiseoteam/SyliusRbacPlugin" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/odiseoteam/SyliusRbacPlugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/odiseoteam/SyliusRbacPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/odiseoteam/SyliusRbacrPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-rbac-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/odiseoteam/sylius-rbac-plugin/downloads" />
    </a>
</p>
<p align="center"><a href="https://sylius.com/partners/odiseo/" target="_blank"><img src="https://github.com/odiseoteam/SyliusRbacPlugin/blob/master/badge-partner-by-sylius.png" width="140"></a></p>

<h1 align="center">Odiseo Sylius Rbac Plugin</h1>

## Description

This plugin provides basic roles and permissions management functionality for Sylius application.
It's highly inspired on the old [RbacPlugin](https://github.com/Sylius/RbacPlugin).

#### Beware!

Adding Write access to a permission automatically means adding Read access.

Write permission access means also updating and deleting.

## Installation

1. Require plugin with composer:

    ```bash
    composer require odiseoteam/sylius-rbac-plugin
    ```

2. Add plugin class and `ProophServiceBusBundle` to your `bundles.php`.

    ```php
    return [
       // ...
       Titi60\SyliusRbacPlugin\OdiseoSyliusRbacPlugin::class => ['all' => true],
    ];
    ```

3. Override AdminUser entity:

a) Use AdministrationRoleAwareTrait and implement AdministrationRoleAwareInterface in the AdminUser class of your Sylius-Standard based project:

```php
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
```

b) And override the model's class in the chosen configuration file (e.g. `config/_sylius.yaml`):

```yaml
sylius_user:
    resources:
        admin:
            user:
                classes:
                    model: App\Entity\AdminUser
```

4. Import routing in `config/routes/sylius_rbac.yaml`:

    ```yaml
    sylius_rbac:
        resource: "@OdiseoSyliusRbacPlugin/Resources/config/routing.yml"
    ```

5. Import configuration in `config/packages/sylius_rbac.yaml`:

    ```yaml
    imports:
        - { resource: "@OdiseoSyliusRbacPlugin/Resources/config/config.yml" }
    ```

6. Copy plugin migrations to your migrations directory (e.g. `src/Migrations`) and apply them to your database:

    ```bash
    cp -R vendor/odiseoteam/sylius-rbac-plugin/migrations/* src/Migrations/
    bin/console doctrine:migrations:migrate
    ```

7. Copy overwritten `SyliusAdminBundle` templates:

    ```bash
    mkdir -p templates/bundles/SyliusAdminBundle
    cp -R vendor/odiseoteam/sylius-rbac-plugin/src/Resources/views/SyliusAdminBundle/* templates/bundles/SyliusAdminBundle/
    ```

8. Run installation command

    ```bash
    bin/console sylius-rbac:install-plugin
    ```

    Which consists of:

    * `sylius:fixtures:load`

        Loading fixture with a default "No sections access" role.

        The command runs in non-interactive mode so it will NOT purge your database.
        However, once you run it again it will throw an exception because of duplicate entry constraint violation.

        If you want to install RBAC plugin again on the same environment you will have to remove all roles manually
        via administration panel or run all commands except `sylius:fixtures:load` separately.

    * `sylius-rbac:normalize-administrators`

        Assigns role created in a previous step to all already existent administrators.

    * `sylius-rbac:grant-access <roleName> <adminSections>`

        Where `adminSections` can be a space-separated list of any of these:
        * catalogManagement
        * configuration
        * customerManagement
        * marketingManagement
        * salesManagement

        #### Beware!

        There are two ways of defining root administrator's email address:

        * Provide it as a parameter in your configuration file (you will not be asked to enter it again via CLI during
        plugin's installation)

        ```yaml
        parameters:
            root_administrator_email: example@example.com
        ```

        * Provide it via CLI

        e.g. `bin/console sylius-rbac:grant-access administrator configuration catalogManagement`

        `In order to permit access to admin panel sections, please provide administrator's email address: sylius@example.com`

        By default, installation command creates *Configurator* role with access granted to all sections.

#### Beware!

You can also use `bin/console sylius-rbac:grant-access-to-given-administrator <email> <roleName> <adminSections>`
command in order to provide an email address as an input parameter.

#### Beware!

`AdminUser` entity references `AdministrationRoleInterface`, which is an abstraction layer above the default
`AdministrationRole` implementation. You can easily customize it by adding a following snippet in your `*.yaml` configuration file:

```yaml
doctrine:
    orm:
        resolve_target_entities:
            Titi60\SyliusRbacPlugin\Entity\AdministrationRoleInterface: FullyQualifiedClassName
```

## Sections configuration

By default, **RbacPlugin** is provided with access configuration for basic Sylius sections (catalog, configuration, customers, marketing and sales) as well as for RBAC section, added by the plugin itself.
Each section has a bunch of route prefixes associated with them, that describes which section gives permissions to which resources management.

However, usually, a Sylius application has a plenty of custom functions within existing or entirely new sections. This plugin allows you to extend its configuration, in order to restrict access to these custom routes.

For the matter of example let's assume we have a simple `Supplier` resource (containing only `string $name` property). It also has already generated routes, that we would like to restrict access to:

- `app_admin_supplier_index`
- `app_admin_supplier_create`
- `app_admin_supplier_update`
- `app_admin_supplier_bulk_delete`
- `app_admin_supplier_delete`

If you don't know how to create and configure custom resource in Sylius application, check out [relevant documentation chapter](https://docs.sylius.com/en/1.3/cookbook/entities/custom-model.html).

### Extending basic Sylius section with new route prefixes

The only thing required to restrict Supplier-related routes with, for example, "Customer management" permission, is adding appropriate route prefix to customers section configuration:

```yaml
odiseo_sylius_rbac:
    sylius_sections:
        customers:
            - app_admin_supplier
```

You would probably also want to add extend "Customers" section in Admin main menu (take a look at [this docs chapter](https://docs.sylius.com/en/1.3/customization/menu.html) for more information).

![Customers sections customized](docs/customers_section_customized.png)

As a result, each Administrator allowed to manage customers in the Admin panel would also be able to manage Suppliers. You may also notice, nothing has changed in permissions configuration form,
as no new section has been added to the RBAC configuration.

![Permissions configuration - no changes](docs/permissions_configuration_no_changes.png)

### Adding a custom section to the application

What if you want to differentiate your new resources management permission? The other possibility is to define your own, custom section in a plugin configuration:

```yaml
odiseo_sylius_rbac:
    custom_sections:
        suppliers:
            - app_admin_supplier
```

> Curiosity: RBAC is also defined as a custom section! You can easily check it out in a plugin source code.

With such a configuration, you should notice a new permission configuration available in the Administration Role form.

![Permissions configuration - no changes](docs/permissions_configuration_changes.png)

To display new permission name nicely, you should also configure a translation in your application's translation file:

```yaml
sylius_rbac:
    ui:
        permission:
            suppliers: Suppliers
```

#### Beware!

You should take into account that by default the RBAC Plugin recognizes the admin-related routes using logic
placed in the `HardcodedRouteNameChecker` class, which is the following:

```php
    public function isAdminRoute(string $routeName): bool
    {
        return
            strpos($routeName, 'sylius_admin') !== false ||
            strpos($routeName, 'sylius_rbac_admin') !== false
        ;
    }
```

Let's assume that you added a new route to your application and you want it to be handled by the RBAC plugin.
Once you did so, you should override the checker placed above and customize it in the following manner:

```php
    public function isAdminRoute(string $routeName): bool
    {
        return
            strpos($routeName, 'sylius_admin') !== false ||
            strpos($routeName, 'sylius_rbac_admin') !== false ||
            strpos($routeName, 'your_custom_phrase' !== false
        ;
    }
```

#### Remember!

When configuring a custom section in Admin main menu, name it the same way you named it under `custom_sections` key in the plugin configuration. It will be automatically hidden and shown, exactly as
basic Sylius sections!

```php
$suppliersSubmenu = $menu->addChild('suppliers')->setLabel('Suppliers');

$suppliersSubmenu
    ->addChild('supplier', ['route' => 'app_admin_supplier_index'])
    ->setLabel('Manage Suppliers')
    ->setLabelAttribute('icon', 'address card outline')
;
```

![Suppliers section](docs/suppliers_section.png)

After these few simple steps, you can already give your custom permission to any already existent Administration role.

## Security issues

If you think that you have found a security issue, please do not use the issue tracker and do not post it publicly.
Instead, all security issues must be sent to `security@sylius.com`.

## Credits

This plugin is maintained by <a href="https://odiseo.io">Odiseo</a>. Want us to help you with this plugin or any Sylius project? Contact us on <a href="mailto:team@odiseo.com.ar">team@odiseo.com.ar</a>.
