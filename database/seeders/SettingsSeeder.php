<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\carbon;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $settings = [

            // General
            [
                'group' => 'general',
                'key' => 'name',
                'value' => 'Shop Name',
                'description' => 'The name of your shop.',
                'structure' => json_encode([
                    'type' => 'text',
                    'label' => 'Site Name',
                    'placeholder' => 'Enter site name',
                ]),
            ],
            [
                'group' => 'general',
                'key' => 'phone',
                'value' => '1234554321',
                'description' => 'Primary contact phone number.',
                'structure' => json_encode([
                    'type' => 'text',
                    'label' => 'Phone',
                    'placeholder' => 'Enter phone number',
                ]),
            ],
            [
                'group' => 'general',
                'key' => 'email',
                'value' => 'example@gmail.com',
                'description' => 'Contact email address.',
                'structure' => json_encode([
                    'type' => 'email',
                    'label' => 'Email',
                    'placeholder' => 'Enter email address',
                ]),
            ],
            [
                'group' => 'general',
                'key' => 'logo',
                'value' => null,
                'description' => 'Site logo image.',
                'structure' => json_encode([
                    'type' => 'file',
                    'label' => 'Logo',
                ]),
            ],
            [
                'group' => 'general',
                'key' => 'faviconImage',
                'value' => null,
                'description' => 'Favicon image.',
                'structure' => json_encode([
                    'type' => 'file',
                    'label' => 'Favicon',
                ]),
            ],
            [
                'group' => 'general',
                'key' => 'address',
                'value' => null,
                'description' => 'Physical address of the shop.',
                'structure' => json_encode([
                    'type' => 'textarea',
                    'label' => 'Address',
                    'placeholder' => 'Enter full address',
                ]),
            ],
            [
                'group' => 'general',
                'key' => 'marquee',
                'value' => null,
                'description' => 'Text displayed in the marquee.',
                'structure' => json_encode([
                    'type' => 'text',
                    'label' => 'Marquee Text',
                    'placeholder' => 'Enter marquee message',
                ]),
            ],

            // Social
            ...collect(['instagram', 'facebook', 'twitter', 'youtube', 'linkedin', 'pinterest'])->map(function ($key) {
                return [
                    'group' => 'social',
                    'key' => $key,
                    'value' => null,
                    'description' => ucfirst($key) . ' profile URL.',
                    'structure' => json_encode([
                        'type' => 'url',
                        'label' => ucfirst($key) . ' URL',
                        'placeholder' => 'Enter ' . ucfirst($key) . ' URL',
                    ]),
                ];
            })->all(),

            // Currency
            [
                'group' => 'currency',
                'key' => 'currency',
                'value' => 'INR',
                'description' => 'Default currency code.',
                'structure' => json_encode([
                    'type' => 'text',
                    'label' => 'Currency Code',
                    'placeholder' => 'e.g. INR, USD',
                ]),
            ],
            [
                'group' => 'currency',
                'key' => 'currency_symbol',
                'value' => '₹',
                'description' => 'Symbol of the currency.',
                'structure' => json_encode([
                    'type' => 'text',
                    'label' => 'Currency Symbol',
                    'placeholder' => 'e.g. ₹, $',
                ]),
            ],
            [
                'group' => 'currency',
                'key' => 'conversion_rate_usd_to_inr',
                'value' => '85',
                'description' => 'Conversion rate from USD to INR.',
                'structure' => json_encode([
                    'type' => 'number',
                    'label' => 'USD to INR Rate',
                    'placeholder' => 'e.g. 85',
                    'step' => '0.01',
                ]),
            ],

            // SEO
            [
                'group' => 'seo',
                'key' => 'meta_title',
                'value' => null,
                'description' => 'SEO meta title.',
                'structure' => json_encode([
                    'type' => 'text',
                    'label' => 'Meta Title',
                    'placeholder' => 'Enter SEO title',
                ]),
            ],
            [
                'group' => 'seo',
                'key' => 'meta_description',
                'value' => null,
                'description' => 'SEO meta description.',
                'structure' => json_encode([
                    'type' => 'textarea',
                    'label' => 'Meta Description',
                    'placeholder' => 'Enter meta description',
                ]),
            ],
            [
                'group' => 'seo',
                'key' => 'meta_keywords',
                'value' => null,
                'description' => 'SEO keywords.',
                'structure' => json_encode([
                    'type' => 'textarea',
                    'label' => 'Meta Keywords',
                    'placeholder' => 'Enter keywords separated by commas',
                ]),
            ],

            // Maintenance
            [
                'group' => 'maintenance',
                'key' => 'maintenance_mode',
                'value' => '0',
                'description' => 'Toggle maintenance mode.',
                'structure' => json_encode([
                    'type' => 'select',
                    'label' => 'Maintenance Mode',
                    'options' => [
                        ['value' => '0', 'label' => 'Disabled'],
                        ['value' => '1', 'label' => 'Enabled'],
                    ]
                ]),
            ],
            [
                'group' => 'maintenance',
                'key' => 'maintenance_message',
                'value' => null,
                'description' => 'Message displayed during maintenance mode.',
                'structure' => json_encode([
                    'type' => 'textarea',
                    'label' => 'Maintenance Message',
                    'placeholder' => 'e.g. We\'ll be back soon!',
                ]),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'group' => $setting['group'],
                    'value' => $setting['value'],
                    'description' => $setting['description'] ?? null,
                    'structure' => $setting['structure'] ?? null,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );
        }
    }
}
