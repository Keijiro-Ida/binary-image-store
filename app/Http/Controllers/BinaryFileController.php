<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BinaryFile;
use App\Models\BinaryType;
use App\Services\BinaryFileService;
use Illuminate\Support\Facades\Auth;

class BinaryFileController extends Controller
{
    protected $imageService;

    public function __construct(BinaryFileService $imageService)
    {
        $this->imageService = $imageService;
    }
    // アップロードフォームの表示
    public function create()
    {
        $userId = Auth::id();

        // ユーザーに関連するバイナリファイルを取得
        $binaryFiles = BinaryFile::where('user_id', $userId)->get();

        // 必要に応じてバイナリタイプも取得
        $binaryTypes = BinaryType::all(); // すべてのバイナリタイプを取得（もし必要であれば）
        $binaryTypeIdToFieldMap = $binaryTypes->pluck('field_name', 'id')->toArray();

        $imageDataArray = [];
        $this->imageService->setBinaryImages($binaryFiles, $binaryTypeIdToFieldMap, $imageDataArray);

        // ビューにデータを渡す
        return view('upload', compact('imageDataArray', 'binaryTypes'));
    }

    public function store(Request $request)
    {

        $validate_rule = $this->imageService->rule();
        $messages = $this->imageService->messages();

        $request->validate($validate_rule, $messages);

        $userId = Auth::id();

        if (!$this->processImage($request, $userId)) {
            return redirect()->back()
                ->with('error', '画像のアップロードに失敗しました。もう一度お試しください。');
        }

        return redirect()->route('upload.create')
            ->with('success', '画像をアップロードしました');

    }

     /**
     * 画像を処理し、バイナリデータとして保存する
     *
     * @param Request $request
     * @param int $userId
     * @return string|null 画像が保存されたパスを返す。保存されなかった場合は null。
     */
    protected function processImage($request, $userId)
    {
        try {

            $binaryTypeId = $request->input('binary_type_id');
            $image_org = $request->file('file');
            $format = $image_org->extension();
            $image = $this->imageService->resize($image_org);

            $fileName = $this->imageService->fileName($binaryTypeId, $userId, $format);
            $binaryImage = $this->imageService->convertToBinary($image);

            $this->imageService->archiveBinaryFile($binaryTypeId, $userId);
            // バイナリイメージを保存
            $this->imageService->saveBinaryFile($binaryTypeId, $binaryImage, $format, $fileName, $userId);

        } catch (\Exception $e) {
            Log::error("画像の処理に失敗しました。" . $e->getMessage());
            return false;
        }

    }
}
