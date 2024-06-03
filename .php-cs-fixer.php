<?php

use PhpCsFixer\Config;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

return (new Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PHP70Migration' => true,
        '@PHP70Migration:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,
        'list_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'operators' => [
                '=' => 'align',
                '=>' => 'align',
                '+=' => 'align',
                '-=' => 'align',
                '*=' => 'align',
                '%=' => 'align',
                '.=' => 'align',
                '^=' => 'align',
            ],
        ],
        'yoda_style' => false,
        'class_definition' => [
            'single_line' => false,
        ],
        'native_function_invocation' => true,
        'native_function_casing' => true,
        'native_constant_invocation' => true,
        'object_operator_without_whitespace' => false,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters', 'match']],
    ]);
