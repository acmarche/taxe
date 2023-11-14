<?php

use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine) {
    $em = $doctrine->orm()->entityManager('default');

    $em->mapping('AcMarcheTaxe')
        ->isBundle(false)
        ->type('annotation')
        ->dir('%kernel.project_dir%/src/AcMarche/Taxe/src/Entity')
        ->prefix('AcMarche\Taxe')
        ->alias('AcMarcheTaxe');
};
