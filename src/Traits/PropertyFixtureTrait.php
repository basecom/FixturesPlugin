<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Traits;

use Basecom\FixturePlugin\Fixture;

/**
 * This trait provides helper methods for fixtures to create property groups and options.
 * It provides typed methods to give all relevant data, while maintaining the ability to extend
 * this with additional data.
 *
 * Notice: This trait is designed to only work with the Fixture class. If you want to use it elsewhere,
 * you need to make sure the FixtureHelper is accessible as `$this->helper`.
 *
 * @mixin Fixture
 */
trait PropertyFixtureTrait
{
    /**
     * @param array<mixed>         $options
     * @param array<string, mixed> $customFields
     * @param array<string, mixed> $rawData
     *
     * @return array<string, mixed>
     */
    protected function createPropertyGroupData(
        string $id,
        string $englishName,
        string $germanName,
        ?array $options = null,
        ?array $customFields = null,
        array $rawData = [],
    ): array {
        // $translations = [];
        // foreach(array_keys($names) as $locale) {
        //     $translations[$locale] = ['name' => $names[$locale]];
        // }

        $data = [
            'id'           => $id,
            'translations' => [
                'de-DE' => [
                    'name' => $germanName,
                ],
                'en-GB' => [
                    'name' => $englishName,
                ],
            ],
            'options'      => $options,
            'customFields' => $customFields,
        ];

        return array_merge_recursive($data, $rawData);
    }

    /**
     * @return array<string, mixed>
     */
    protected function createPropertyOptionData(
        string $id,
        string $englishName,
        string $germanName,
        ?string $colorHexCode = null,
        ?string $coverUrl = null,
        ?string $coverAlt = null,
    ): array {
        return [
            'id'           => $id,
            'translations' => [
                'de-DE' => [
                    'name' => $germanName,
                ],
                'en-GB' => [
                    'name' => $englishName,
                ],
            ],
            'customFields' => [
                'property_group_option_cover_url' => $coverUrl,
                'property_group_option_cover_alt' => $coverAlt,
            ],
            'colorHexCode' => $colorHexCode,
        ];
    }
}
