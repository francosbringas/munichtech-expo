<?php

namespace App\Support;

class PhoneCountryPrefixes
{
    /**
     * Country calling codes sorted alphabetically by English country name.
     *
     * @return array<int, array{code: string, flag: string, name: string}>
     */
    public static function all(): array
    {
        $prefixes = [
            ['code' => '+54', 'flag' => '🇦🇷', 'name' => 'Argentina'],
            ['code' => '+61', 'flag' => '🇦🇺', 'name' => 'Australia'],
            ['code' => '+43', 'flag' => '🇦🇹', 'name' => 'Austria'],
            ['code' => '+32', 'flag' => '🇧🇪', 'name' => 'Belgium'],
            ['code' => '+55', 'flag' => '🇧🇷', 'name' => 'Brazil'],
            ['code' => '+56', 'flag' => '🇨🇱', 'name' => 'Chile'],
            ['code' => '+86', 'flag' => '🇨🇳', 'name' => 'China'],
            ['code' => '+57', 'flag' => '🇨🇴', 'name' => 'Colombia'],
            ['code' => '+420', 'flag' => '🇨🇿', 'name' => 'Czech Republic'],
            ['code' => '+45', 'flag' => '🇩🇰', 'name' => 'Denmark'],
            ['code' => '+20', 'flag' => '🇪🇬', 'name' => 'Egypt'],
            ['code' => '+358', 'flag' => '🇫🇮', 'name' => 'Finland'],
            ['code' => '+33', 'flag' => '🇫🇷', 'name' => 'France'],
            ['code' => '+49', 'flag' => '🇩🇪', 'name' => 'Germany'],
            ['code' => '+30', 'flag' => '🇬🇷', 'name' => 'Greece'],
            ['code' => '+36', 'flag' => '🇭🇺', 'name' => 'Hungary'],
            ['code' => '+91', 'flag' => '🇮🇳', 'name' => 'India'],
            ['code' => '+972', 'flag' => '🇮🇱', 'name' => 'Israel'],
            ['code' => '+39', 'flag' => '🇮🇹', 'name' => 'Italy'],
            ['code' => '+81', 'flag' => '🇯🇵', 'name' => 'Japan'],
            ['code' => '+82', 'flag' => '🇰🇷', 'name' => 'South Korea'],
            ['code' => '+52', 'flag' => '🇲🇽', 'name' => 'Mexico'],
            ['code' => '+212', 'flag' => '🇲🇦', 'name' => 'Morocco'],
            ['code' => '+31', 'flag' => '🇳🇱', 'name' => 'Netherlands'],
            ['code' => '+234', 'flag' => '🇳🇬', 'name' => 'Nigeria'],
            ['code' => '+47', 'flag' => '🇳🇴', 'name' => 'Norway'],
            ['code' => '+48', 'flag' => '🇵🇱', 'name' => 'Poland'],
            ['code' => '+351', 'flag' => '🇵🇹', 'name' => 'Portugal'],
            ['code' => '+40', 'flag' => '🇷🇴', 'name' => 'Romania'],
            ['code' => '+7', 'flag' => '🇷🇺', 'name' => 'Russia'],
            ['code' => '+966', 'flag' => '🇸🇦', 'name' => 'Saudi Arabia'],
            ['code' => '+65', 'flag' => '🇸🇬', 'name' => 'Singapore'],
            ['code' => '+27', 'flag' => '🇿🇦', 'name' => 'South Africa'],
            ['code' => '+34', 'flag' => '🇪🇸', 'name' => 'Spain'],
            ['code' => '+46', 'flag' => '🇸🇪', 'name' => 'Sweden'],
            ['code' => '+41', 'flag' => '🇨🇭', 'name' => 'Switzerland'],
            ['code' => '+90', 'flag' => '🇹🇷', 'name' => 'Turkey'],
            ['code' => '+380', 'flag' => '🇺🇦', 'name' => 'Ukraine'],
            ['code' => '+971', 'flag' => '🇦🇪', 'name' => 'United Arab Emirates'],
            ['code' => '+44', 'flag' => '🇬🇧', 'name' => 'United Kingdom'],
            ['code' => '+1', 'flag' => '🇺🇸', 'name' => 'United States'],
        ];

        usort($prefixes, fn ($a, $b) => strcasecmp($a['name'], $b['name']));

        return $prefixes;
    }

    public static function defaultCode(): string
    {
        return '+49';
    }
}
