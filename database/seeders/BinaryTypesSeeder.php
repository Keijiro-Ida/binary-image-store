<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BinaryTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('binary_types')->insert([
            [
                'type_name' => 'ID Card',
                'type_name_jp' => '身分証明書',
                'description' => 'Identification card, such as a driver’s license or state ID.',
                'description_jp' => '運転免許証や住民基本台帳カードなどの身分証明書。',
                'field_name' => 'id_card',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type_name' => 'Passport',
                'type_name_jp' => 'パスポート',
                'description' => 'International travel document issued by a country’s government.',
                'description_jp' => '各国の政府が発行する国際旅行用の文書。',
                'field_name' => 'passport',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type_name' => 'Receipt',
                'type_name_jp' => '領収書',
                'description' => 'Proof of purchase document.',
                'description_jp' => '購入の証明となる文書。',
                'field_name' => 'receipt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type_name' => 'Contract',
                'description' => 'Legally binding agreement between parties.',
                'type_name_jp' => '契約書',
                'description_jp' => '当事者間の法的拘束力のある合意を示す文書。',
                'field_name' => 'contract',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type_name' => 'Invoice',
                'type_name_jp' => '請求書',
                'description' => 'Document listing goods or services provided with their costs.',
                'description_jp' => '提供された商品やサービスとその費用を一覧にした文書。',
                'field_name' => 'invoice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
