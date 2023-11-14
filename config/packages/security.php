<?php

use AcMarche\Extranet\Security\ExtranetAutenticator;
use AcMarche\Taxe\Entity\User;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {

    $security->provider('taxe_user_provider', [
        'entity' => [
            'class' => User::class,
            'property' => 'email',
        ],
    ]);

    // @see Symfony\Config\Security\FirewallConfig
    $main = [
        'provider' => 'taxe_user_provider',
        'logout' => ['path' => 'app_logout'],
        'form_login' => [],
        'entry_point' => ExtranetAutenticator::class,
        'custom_authenticators' => [ExtranetAutenticator::class],
    ];

    $security->firewall('main', $main);
};
