<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\BinaryFile;
use App\Services\BinaryFileService;

class ImageServiceTest extends TestCase
{
    protected $fileService;

    protected function setUp(): void
    {
        $this->fileService = new BinaryFileService();
    }

    /**
     * A basic unit test example.
     */
    public function testGenerateImageData(): void
    {

            $binaryFile = new BinaryFile();
            $binaryFile->binary_data = 'fake_binary_data';
            $binaryFile->file_format = 'jpeg';

            // 期待されるBase64形式の画像データ
            $expectedImageData = "data:image/jpeg;base64," . base64_encode('fake_binary_data');

            // 実際のメソッドの結果を取得
            $actualImageData = $this->fileService->generateImageData($binaryFile);

            // 期待値と実際の値が一致するかをテスト
            $this->assertEquals($expectedImageData, $actualImageData);

    }
}
