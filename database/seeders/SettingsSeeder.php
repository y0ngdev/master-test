<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'id' => 34,
                'key' => 'general.site_name',
                'value' => 'MasteringX',
                'type' => 'string',
                'created_at' => '2025-07-01 10:59:48',
                'updated_at' => '2025-07-08 11:50:06',
            ],
            [
                'id' => 35,
                'key' => 'general.site_description',
                'value' => 'The most comprehensive course on X.',
                'type' => 'string',
                'created_at' => '2025-07-01 10:59:49',
                'updated_at' => '2025-07-08 00:26:22',
            ],
            [
                'id' => 36,
                'key' => 'general.site_logo',
                'value' => 'site/01JZMM9BDX16EM4A97T4WPFPNF.png',
                'type' => 'string',
                'created_at' => '2025-07-01 10:59:49',
                'updated_at' => '2025-07-08 09:01:24',
            ],
            [
                'id' => 37,
                'key' => 'general.site_favicon',
                'value' => 'site/01JZMM9BJHAGEQSFVYDZENAB07.png',
                'type' => 'string',
                'created_at' => '2025-07-01 10:59:49',
                'updated_at' => '2025-07-08 09:01:24',
            ],
            [
                'id' => 38,
                'key' => 'landing.hero_title',
                'value' => "Teach. Inspire. We'll Handle the Tech.",
                'type' => 'string',
                'created_at' => '2025-07-01 10:59:49',
                'updated_at' => '2025-07-03 13:17:01',
            ],
            [
                'id' => 39,
                'key' => 'landing.hero_subtitle',
                'value' => 'Upload your lessons and reach your students—no hosting headaches, no distractions. Focus on what you do best: teaching.',
                'type' => 'string',
                'created_at' => '2025-07-01 10:59:49',
                'updated_at' => '2025-07-03 13:17:02',
            ],
            [
                'id' => 40,
                'key' => 'landing.hero_cta_text',
                'value' => 'Buy Now',
                'type' => 'string',
                'created_at' => '2025-07-01 10:59:49',
                'updated_at' => '2025-07-03 13:17:03',
            ],
            [
                'id' => 41,
                'key' => 'landing.features',
                'value' => json_encode([
                    'section' => [
                        'title' => "What you'll get",
                        'subtitle' => 'Everything you need to create professional videos',
                    ],
                    'items' => [
                        [
                            'title' => 'HD Video Lessons',
                            'description' => 'Crystal-clear 4K video tutorials with professional audio quality',
                            'icon' => 'lucide:video',
                        ],
                        [
                            'title' => 'Lifetime Access',
                            'description' => 'Learn at your own pace with unlimited access to all course content',
                            'icon' => 'lucide:clock',
                        ],
                        [
                            'title' => 'Project Files ',
                            'description' => 'Download source files and assets to follow along with the lessons',
                            'icon' => 'lucide:file-text',
                        ],
                    ],
                ]),
                'type' => 'json',
                'created_at' => '2025-07-01 10:59:49',
                'updated_at' => '2025-07-05 08:42:21',
            ],
            [
                'id' => 42,
                'key' => 'landing.faq',
                'value' => json_encode([
                    'title' => 'Frequently asked questions',
                    'description' => 'Everything you need to know about the platform',
                    'items' => [
                        [
                            'question' => 'What video formats are supported?',
                            'answer' => 'We support all major video formats including MP4, MOV, AVI, and WMV. Our platform automatically optimizes your videos for the best viewing experience across all devices.',
                        ],
                        [
                            'question' => 'How secure is my content?',
                            'answer' => 'Your content is protected with enterprise-grade security including DRM, encrypted storage, and customizable access controls. We also offer domain restrictions and watermarking features.',
                        ],
                        [
                            'question' => 'Can I migrate my existing courses?',
                            'answer' => 'Yes! We offer free migration assistance for all your existing course content. Our team will help you transfer videos, course structure, and student data seamlessly.',
                        ],
                        [
                            'question' => 'What payment methods do you accept?',
                            'answer' => 'We accept payments through stripe.',
                        ],
                        [
                            'question' => 'How do I get support if I need help?',
                            'answer' => 'We offer multiple support channels including 24/7 email support, live chat, and comprehensive documentation. Pro and Enterprise plans also get priority support and dedicated account managers.',
                        ],
                    ],
                ]),
                'type' => 'json',
                'created_at' => '2025-07-01 10:59:50',
                'updated_at' => '2025-07-01 11:14:53',
            ],
            [
                'id' => 48,
                'key' => 'general.socials',
                'value' => json_encode([
                    'email' => 'masteringx@example.com',
                    'twitter' => 'y0ngdevdeveloper',
                    'linkedin' => 'yongdev',
                    'youtube' => '@y0ngdev',
                    'github' => 'y0ngdev',
                ]),
                'type' => 'json',
                'created_at' => '2025-07-01 11:56:46',
                'updated_at' => '2025-07-03 14:02:51',
            ],
            [
                'id' => 51,
                'key' => 'landing.instructor',
                'value' => json_encode([
                    'section' => [
                        'title' => 'Meet your instructor',
                    ],
                    'name' => 'Yongdev',
                    'title' => 'Senior Course Creator',
                    'about' => 'Former Creative Director with 10+ years of experience in video production and online education. Helping creators build successful online courses and digital products.',
                    'socials' => [
                        'twitter' => 'y0ngdev',
                        'linkedin' => 'y0ngdev',
                        'youtube' => 'y0ngdev',
                        'github' => 'y0ngdev',
                        'website' => 'https://masteringx.test/',
                    ],
                    'image' => 'site/01JZ8AB6GXXTZFF1CM7DEXXJX1.jpg',
                ]),
                'type' => 'json',
                'created_at' => '2025-07-03 14:02:51',
                'updated_at' => '2025-07-03 14:16:44',
            ],
            [
                'id' => 52,
                'key' => 'landing.hero_image',
                'value' => 'site/01JZD1908Q6A7AN13T23QF2QT3.jpg',
                'type' => 'string',
                'created_at' => '2025-07-05 10:14:27',
                'updated_at' => '2025-07-05 10:14:27',
            ],
        ]);
    }
}
