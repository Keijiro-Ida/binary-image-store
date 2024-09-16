<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Models\BinaryFile;

class BinaryFileService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }
    /**
     * 画像ファイル名を生成するメソッド
     *
     * 現在の日時、ファイルID、ファイルタイプを基にユニークな画像ファイル名を生成
     *
     * @param string $fileTypeId
     * @param int $userId
     * @param string $format
     * @return string 生成された画像ファイル名
     */
    public function fileName($fileTypeId, $userId, $format)
    {
        $now = Carbon::now()->format('Ymd_His');
        return $now . '_' . $fileTypeId . '_' . $userId . '.' . $format;
    }

    /**
     * 画像オブジェクトをバイナリデータに変換するメソッド
     *
     * 画像オブジェクトをバイナリ形式でエンコードし、文字列として返す
     *
     * @param \Intervention\Image\Image $image
     * @return string バイナリ形式の画像データ
     */
    public function convertToBinary($image)
    {
        return (string) $image->encode();
    }

    /**
     * 画像を指定サイズにリサイズするメソッド
     *
     * @param \Intervention\Image\Image $image_org
     * @return \Intervention\Image\Image リサイズ後の画像オブジェクト
     */
    public function resize($image_org)
    {
        try {
            $image = $this->manager->read($image_org);

            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // リサイズする幅を指定
            $newWidth = 700;
            // 縦横比を保ったまま高さを計算
            $newHeight = intval(($originalHeight / $originalWidth) * $newWidth);

            // 画像をリサイズ
            $image->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();

            });

            return $image;

        } catch (Exception $e) {
            throw new Exception("画像のリサイズに失敗しました。:
            {$e->getMessage()}");
        }

    }

    /**
     * 画像フィールドのバリデーションルールを取得するメソッド
     *
     * @return array 画像フィールドに対応するバリデーションルールの配列
     */
    public function rule($fieldName = 'file') {

        return [
            "$fieldName" => 'file|mimes:jpeg,png,jpg|max:15360',
        ];
    }

    /**
     * 画像フィールドのバリデーションエラーメッセージを取得するメソッド
     *
     * @return array 画像フィールドに対応するエラーメッセージの配列
     */
    public function messages($fieldName = 'file')
    {
        $commonMessages = [
            'max' => "画像のファイルサイズが許可された最大サイズを超えています。\nサイズを調整して再度アップロードしてください。",
            'mimes' => '写真の形式は、jpeg、png、jpgのみです',
        ];

        $messages = [];

        foreach($commonMessages as $rule => $message) {
            $messages["{$fieldName}.{$rule}"] = $message;
        }

        return $messages;
    }

    /**
     * 過去のバイナリファイルをアーカイブするメソッド
     *
     * 古いバイナリファイルのフィールドをリセットし、アーカイブする
     *
     * @param int $binaryTypeId
     * @param int $userId
     * @return void
     */
    public function archiveBinaryFile($binaryTypeId, $userId)
    {
        $pastBinaryFile = BinaryFile::where('is_deleted', false)
            ->where('user_id', $userId)
            ->where('binary_type_id', $binaryTypeId)
            ->get();

        if ($pastBinaryFile->isEmpty()) {
            return;
        }

        foreach ($pastBinaryFile as $file) {
            $file->is_deleted = true;
            $file->save();
        }
    }

    /**
     * バイナリファイルを保存するメソッド
     *
     * 画像データをデータベースに保存
     *
     * @param string $field 対象フィールド（例: 'customer_id'）
     * @param int $binaryTypeId
     * @param string $binaryImage
     * @param string $format
     * @param string $fileName
     * @param int $userId
     * @return void
     */
    public function saveBinaryFile($binaryTypeId, $binaryImage, $format, $fileName, $userId)
    {
        $image = new BinaryFile();
        $image->binary_type_id = $binaryTypeId;
        $image->binary_data = $binaryImage;
        $image->file_format = $format; // 例: 'jpeg' または 'png'
        $image->file_name = $fileName;
        $image->user_id = $userId;
        $image->save();
    }

    /**
     * バイナリファイルから画像データを生成
     *
     * @param object $binaryFile
     * @return string Base64エンコードされた画像データを含む文字列。HTMLで使用可能な "data:image/..." 形式。
     */
    public function generateImageData($binaryFile)
    {
        $imageData = "data:image/$binaryFile->file_format;base64," . base64_encode($binaryFile->binary_data);

        return $imageData;
    }

    /**
     * バイナリファイルのリストを処理し、対応する画像データを指定された配列に設定
     *
     * @param array $binaryFiles バイナリファイルオブジェクトの配列。
     * @param array $binaryTypeIdToFieldMap バイナリタイプIDをフィールド名にマッピングする配列。
     * @param array &$imageDataArray 画像データを設定するための参照渡し配列。フィールド名をキーとし、Base64エンコードされた画像データを値とする。
     * @return void
     */
    public function setBinaryImages($binaryFiles, $binaryTypeIdToFieldMap, &$imageDataArray)
    {
        if (empty($binaryFiles)) {
            return;
        }

        foreach ($binaryFiles as $binaryFile) {
            $binaryTypeId = $binaryFile->binary_type_id;

            if (isset($binaryTypeIdToFieldMap[$binaryTypeId])) {
                $imageKey = $binaryTypeIdToFieldMap[$binaryTypeId];
                $imageData = $this->generateImageData($binaryFile);

                // 画像データが生成された場合のみ設定
                if ($imageData !== null) {
                    // 画像データと種別情報を配列に格納
                    $imageDataArray[] = [
                        'type' => $imageKey, // 種別名（例: "JPEG", "PNG"など）
                        'image' => $imageData, // Base64エンコードされた画像データ
                    ];
                }
            }
        }
    }
}
