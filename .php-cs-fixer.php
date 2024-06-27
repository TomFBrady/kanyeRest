<?php

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@Symfony' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
])
->setFinder(
    PhpCsFixer\Finder::create()
        ->in(__DIR__)
);