<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'من نحن',
                'content' => '<h2>من نحن</h2><p>نحن شركة متخصصة في خدمات تنظيف السيارات، نقدم أفضل الخدمات لعملائنا بجودة عالية واحترافية.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'الشروط والأحكام',
                'content' => '<h2>الشروط والأحكام</h2><p>يرجى قراءة هذه الشروط والأحكام بعناية قبل استخدام خدماتنا.</p><ul><li>يجب على المستخدم الالتزام بجميع الشروط المذكورة</li><li>الشركة غير مسؤولة عن أي أضرار ناتجة عن سوء الاستخدام</li><li>يحق للشركة تعديل الشروط في أي وقت</li></ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\Page::insert($pages);
    }
}
