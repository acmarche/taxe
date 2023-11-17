<?php

use AcMarche\Taxe\Entity\User;
use AcMarche\Taxe\Security\TaxeAuthenticator;
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
                    ],
                ],
            ],
        ]
    );

    $authenticators = [TaxeAuthenticator::class];

    $main = [
        'provider' => 'taxe_user_provider',
        'logout' => [
            'path' => 'app_logout',
        ],
        'form_login' => [],
        'entry_point' => TaxeAuthenticator::class,
        'switch_user' => true,
        'login_throttling' => [
            'max_attempts' => 6, //per minute...
        ],
    ];

    /*  if (interface_exists(LdapInterface::class)) {
          $authenticators[] = MercrediLdapAuthenticator::class;
          $main['form_login_ldap'] = [
              'service' => Ldap::class,
              'check_path' => 'app_login',
          ];
      }*/

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
