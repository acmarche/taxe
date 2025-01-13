<?php

use Symfony\Config\TwigConfig;

return static function (TwigConfig $twigConfig): void {
    $twigConfig
        ->path('%kernel.project_dir%/src/AcMarche/Taxe/templates', 'Taxe')
        ->formThemes(['bootstrap_5_layout.html.twig']);
};
