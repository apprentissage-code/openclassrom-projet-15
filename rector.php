<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
  ->withPaths([
    __DIR__ . '/config',
    __DIR__ . '/public',
    __DIR__ . '/src',
    __DIR__ . '/tests',
  ])
  ->withSets([
    SetList::PHP_81,
    SymfonySetList::SYMFONY_64,
    SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
  ]);
