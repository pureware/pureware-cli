<?php declare(strict_types=1);

use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    // here we can define, what sets of rules will be applied
    // tip: use "SetList" class to autocomplete sets
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE
    ]);
    $rectorConfig->paths(
        [
            __DIR__ . '/src',
            __DIR__ . '/tests'
        ]
    );
    $rectorConfig->skip(
        [
            __DIR__ . '/src/Resources'
        ]
    );

    $rectorConfig->rules(
        rectorClasses: [
            PreferThisOrSelfMethodCallRector::class,
            TypedPropertyRector::class
        ]
    );
};
