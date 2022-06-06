<?php

declare(strict_types=1);

namespace App;

final class InMemoryProducts
{
    /**
     * @var mixed[]
     */
    private static array $products = [
        [
            'sku' => '9325336130810',
            'name' => 'Game of Thrones: Season 1',
            'price' => '39.49',
        ],
        [
            'sku' => '9325336028278',
            'name' => 'The Fresh Prince of Bel-Air',
            'price' => '19.99',
        ],
        [
            'sku' => '9780201835953',
            'name' => 'The Mythical Man-Month',
            'price' => '31.87',
        ],
        [
            'sku' => '9781430219484',
            'name' => 'Coders at Work',
            'price' => '28.72',
        ],
        [
            'sku' => '9780132071482',
            'name' => 'Artificial Intelligence',
            'price' => '119.92',
        ],
    ];

    public static function all(): array
    {
        return self::$products;
    }

    public static function findOneBySku(string $sku): array
    {
        $key = \array_search($sku, \array_column(self::$products, 'sku'), true);

        return self::$products[$key];
    }
}