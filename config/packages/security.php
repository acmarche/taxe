<?php

use AcMarche\Taxe\Entity\User;
use AcMarche\Taxe\Security\TaxeAuthenticator;
use AcMarche\Taxe\Security\TaxeLdapAuthenticator;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [
        'password_hashers' => [
            User::class => [
                'algorithm' => 'auto',
            ],
        ],
    ]);

    $containerConfigurator->extension(
        'security',
        [
            'providers' => [
                'taxe_user_provider' => [
                    'entity' => [
                        'class' => User::class,
                        'property' => 'username',
                    ],
                ],
            ],
        ]
    );

    $main = [
        'provider' => 'taxe_user_provider',
        'logout' => [
            'path' => 'app_logout',
        ],
        'login_throttling' => [
            'max_attempts' => 6, // per minute...
        ],
        'remember_me' => [
            'secret' => '%kernel.secret%',
            'lifetime' => 604800,
            'path' => '/',
            'always_remember_me' => true,
        ],
        'form_login' => [],
        'entry_point' => TaxeAuthenticator::class,
        'switch_user' => true,
    ];

    $authenticators = [TaxeAuthenticator::class];
    if (interface_exists(LdapInterface::class)) {
        $authenticators[] = TaxeLdapAuthenticator::class;
        $main['form_login_ldap'] = [
            'service' => Ldap::class,
            'check_path' => 'app_login',
        ];
    }

    $main['custom_authenticator'] = $authenticators;

    $containerConfigurator->extension(
        'security',
        [
            'firewalls' => [
                'main' => $main,
            ],
        ]
    );

};
