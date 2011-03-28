Provides user persistence for your Symfony2 Project.

Features
========

- Compatible with Doctrine ORM **and** ODM thanks to a generic repository.
- Model is extensible at will
- REST-ful authentication
- Current user available in your controllers and views
- Unit tested and functionally tested

Installation
============

Add UserBundle to your vendor/bundles/ dir
-------------------------------------

::

    $ git submodule add git://github.com/FriendsOfSymfony/UserBundle.git vendor/bundles/FOS/UserBundle

Add the FOS namespace to your autoloader
----------------------------------------

::

    // app/autoload.php
    $loader->registerNamespaces(array(
        'FOS' => __DIR__.'/../vendor/bundles',
        // your other namespaces
    );

Add UserBundle to your application kernel
-----------------------------------------

::

    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new FOS\UserBundle\FOSUserBundle(),
            // ...
        );
    }

Create your User class
----------------------

You must create a User class that extends either the entity or document abstract
User class in UserBundle.  All fields on the base class are mapped, except for
``id`` and ``groups``; this is intentional, so you can select the generator that
best suits your application, and are able to use a custom Group model class.
Feel free to add additional properties and methods to your custom class.

ORM User class:
~~~~~~~~~~~~~~~

::

    // src/MyProject/MyBundle/Entity/User.php

    namespace MyProject\MyBundle\Entity;
    use FOS\UserBundle\Entity\User as BaseUser;

    /**
     * @orm:Entity
     */
    class User extends BaseUser
    {
        /**
         * @orm:Id
         * @orm:Column(type="integer")
         * @orm:generatedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @orm:ManyToMany(targetEntity="FOS\UserBundle\Entity\DefaultGroup")
         * @orm:JoinTable(name="fos_user_user_group",
         *      joinColumns={@orm:JoinColumn(name="user_id", referencedColumnName="id")},
         *      inverseJoinColumns={@orm:JoinColumn(name="group_id", referencedColumnName="id")}
         * )
         */
        protected $groups;
    }

MongoDB User class:
~~~~~~~~~~~~~~~~~~~

::

    // src/MyProject/MyBundle/Document/User.php

    namespace MyProject\MyBundle\Document;
    use FOS\UserBundle\Document\User as BaseUser;

    /**
     * @mongodb:Document
     */
    class User extends BaseUser
    {
        /** @mongodb:Id(strategy="auto") */
        protected $id;

        /** @mongodb:ReferenceMany(targetDocument="FOS\UserBundle\Document\DefaultGroup") */
        protected $groups;
    }

Group class
-----------

To customize the Group class you can define your own entity extending the mapped
superclass of the Bundle ``FOS\UserBundle\Entity\Group``. If you don't want to
extend it you can use the entity provided by the bundle which is
``FOS\UserBundle\Entity\DefaultGroup``.
Same is available for MongoDB in the ``Document`` subnamespace.

Configure your project
----------------------

The UserBundle works with the Symfony Security Component, so make sure that is
enabled in your kernel and in your project's configuration::

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            // ...
        );
    }

    # app/config/config.yml
    security:
        providers:
            # the naming of a security provider is up to you, we chose "fos_userbundle"
            fos_userbundle:
                id: fos_user.user_manager

Note::

    You need to activate SwiftmailerBundle to be able to use the functionalities
    using emails (confirmation of the account, resetting of the password).
    See the `Emails` section to know how using another mailer.

The login form and all the routes used to create a user and reset the password
have to be available to unauthenticated users but using the same firewall as
the pages you want to securize with the bundle. Assuming you import the
user.xml routing file with the ``/user`` prefix they will be::

    /login
    /user/new
    /user/check-confirmation-email
    /user/confirm/{token}
    /user/confirmed
    /user/request-reset-password
    /user/send-resetting-email
    /user/check-resetting-email
    /user/reset-password/{token}

You also have to include the UserBundle in your Doctrine mapping configuration,
along with the bundle containing your custom User class::

    # app/config/config.yml
    doctrine:
        orm:
            mappings:
                FOSUserBundle: ~
                MyProjectMyBundle:   ~
                # your other bundles

The above example assumes an ORM configuration, but the `mappings` configuration
block would be the same for MongoDB ODM.

Minimal configuration
---------------------

At a minimum, your configuration must define your DB driver ("orm" or "mongodb"),
a User class and the provider key. The provider key matches the key in the firewall
configuration that is used for users with the UserController.

The provider key needs to be configured so that the UserBundle can determine
against what firewall the user should be authenticated after activating the
account for example. This means that out of the box UserBundle only supports
being used for a single firewall, though with a custom Controller this limitation
can be circumvented.

For example for a security configuration like the following the provider_key would
have to be set to "main", as shown in the proceeding examples:

::

    # app/config/config.yml
    security:
        providers:
            fos_userbundle:
                id: fos_user.user_manager

        firewalls:
            main:
                form_login:
                    provider: fos_userbundle

ORM
~~~

In YAML:

::

    # app/config/config.yml
    fos_user:
        db_driver: orm
        provider_key: main
        class:
            model:
                user: MyProject\MyBundle\Entity\User
                group: FOS\UserBundle\Entity\DefaultGroup

Or if you prefer XML:

::

    # app/config/config.xml

    <fos_user:config db-driver="orm" provider-key="fos_userbundle">
        <fos_user:class>
            <fos_user:model
                user="MyProject\MyBundle\Entity\User"
                group="FOS\UserBundle\Entity\DefaultGroup"
            />
        </fos_user:class>
    </fos_user:config>

ODM
~~~

In YAML:

::

    # app/config/config.yml
    fos_user:
        db_driver: mongodb
        provider_key: fos_userbundle
        class:
            model:
                user: MyProject\MyBundle\Document\User
                group: FOS\UserBundle\Document\DefaultGroup

Or if you prefer XML:

::

    # app/config/config.xml

    <fos_user:config db-driver="mongodb" provider-key="fos_userbundle">
        <fos_user:class>
            <fos_user:model
                user="MyProject\MyBundle\Document\User"
                group="FOS\UserBundle\Entity\DefaultGroup"
            />
        </fos_user:class>
    </fos_user:config>


Add authentication routes
-------------------------

If you want ready to use login and logout pages, include the built-in
routes:

::

    # app/config/routing.yml
    fos_user_security:
        resource: @FOSUserBundle/Resources/config/routing/security.xml

    fos_user_user:
        resource: @FOSUserBundle/Resources/config/routing/user.xml
        prefix: /user

::

    # app/config/routing.xml

    <import resource="@FOSUserBundle/Resources/config/routing/security.xml"/>
    <import resource="@FOSUserBundle/Resources/config/routing/user.xml" prefix="/user" />

You now can login at http://app.com/login

You can also import the group.xml file to use the builtin controllers to
manipulate the groups.

Command line
============

UserBundle provides command line utilities to help manage your
application users.

Create user
-----------

This command creates a new user::

    $ php app/console fos:user:create username email password

If you don't provide the required arguments, a interactive prompt will
ask them to you::

    $ php app/console fos:user:create

Promote user as a super administrator
-------------------------------------

This command promotes a user as a super administrator::

    $ php app/console fos:user:promote

User manager service
====================

UserBundle works with both ORM and ODM. To make it possible, it wraps
all the operation on users in a UserManager. The user manager is a
service of the container.

If you configure the db_driver to orm, this service is an instance of
``FOS\UserBundle\Entity\UserManager``.

If you configure the db_driver to odm, this service is an instance of
``FOS\UserBundle\Document\UserManager``.

Both these classes implement ``FOS\UserBundle\Model\UserManagerInterface``.

Access the user manager service
-------------------------------

If you want to manipulate users in a way that will work as well with
ORM and ODM, use the fos_user.user_manager service::

    $userManager = $container->get('fos_user.user_manager');

That's the way UserBundle's internal controllers are built.

Access the current user class
-----------------------------

A new instance of your User class can be created by the user manager::

    $user = $userManager->createUser();

`$user` is now an Entity or a Document, depending on the configuration.

Configuration example:
======================

All configuration options are listed below::

    # app/config/config.yml
    fos_user:
        db_driver:    mongodb
        provider_key: fos_userbundle
        class:
            model:
                user:  MyProject\MyBundle\Document\User
                group: MyProject\MyBundle\Document\Group
            form:
                user:            ~
                group:           ~
                change_password: ~
                reset_password:  ~
            controller:
                user:     ~
                security: ~
                group:    ~
            util:
                email_canonicalizer:    ~
                username_canonicalizer: ~
        service:
            util:
                mailer: ~
        encoder:
            algorithm:        ~
            encode_as_base64: ~
            iterations:       ~
        form_name:
            user:            ~
            group:           ~
            change_password: ~
            reset_password:  ~
        form_validation_groups:
            user: ~             # This value is an array of groups
        email:
            from_email: ~       # { admin@example.com: Sender_name }
            confirmation:
                enabled:    ~
                template:   ~
            resetting_password:
                template:   ~
                token_ttl:  ~
        template:
            engine: ~
            theme:  ~

Security configuration
----------------------

Here is an example of a full security configuration using FOSUserBundle::

    # app/config/security.yml
    security:
        providers:
            fos_userbundle:
                id: fos_user.user_manager

        firewalls:
            main:
                pattern:      .*
                form-login:
                    provider:       fos_userbundle
                    login_path:     /login
                    use_forward:    false
                    check_path:     /login_check
                    failure_path:   null
                logout:       true
                anonymous:    true

        access_control:
            # The WDT has to be allowed to anonymous users to avoid requiring the login with the AJAX request
            - { path: /_wdt/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /_profiler/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }
            # URL of the bundles which need to be available to anonymous users
            - { path: /login, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /login_check, role: IS_AUTHENTICATED_ANONYMOUSLY } # for the case of a failed login
            - { path: /user/new, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /user/check-confirmation-email, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /user/confirm/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /user/confirmed, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /user/request-reset-password, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /user/send-resetting-email, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /user/check-resetting-email, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: /user/reset-password/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }
            # Secured part of the site (all site here and an admin part for admin users)
            - { path: /admin/.*, role: ROLE_ADMIN }
            - { path: /.*, role: ROLE_USER }

        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPERADMIN:  ROLE_ADMIN

Replacing some part by your own implementation:
===============================================

Templating
----------

The template names are not configurable, however Symfony2 makes it possible
to extend a bundle by creating a new Bundle and implementing a getParent()
method inside that new Bundle's definition::

    class MyProjectUserBundle extends Bundle
    {
        public function getParent()
        {
            return 'FOSUserBundle';
        }
    }

For example ``vendor/bundles/FOS/UserBundle/Resources/views/User/new.twig`` can be
replaced inside an application by putting a file with alternative content in
``src/MyProject/UserBundle/Resources/views/User/new.twig``.

You can use a different templating engine by configuring it but you will have to
create all the needed templates as only twig templates are provided.

Validation
----------

The ``Resources/config/validation.xml`` file contains definitions for custom
validator rules for various classes. The rules for the ``User`` class are all in
the ``Registration`` validation group so you can choose not to use them.

Emails
------

The default mailer relies on Swiftmailer to send the mails of the bundle. If you
want to use another mailer in your project you can change it by defining your
own service implementing ``FOS\UserBundle\Util\MailerInterface`` and setting its
id in the configuration::

    fos_user:
        # ...
        service:
            util:
                mailer: custom_mailer_id

Canonicalization
----------------

``Canonicalizer`` services are used to canonicalize the username and the email
fields for database storage. By default, username and email fields are canonicalized
in the same manner using ``mb_convert_case()``. You may configure your own class
for each field provided it implements ``FOS\UserBundle\Util\CanonicalizerInterface``.

Note::

    If you do not have the mbstring extension installed you will need to
    define your own ``canonicalizer``.
