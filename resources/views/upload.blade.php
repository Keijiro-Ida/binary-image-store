<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像ギャラリー</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .image-thumbnail {
            width: 100%;
            height: auto;
            max-width: 200px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>画像ギャラリー</h2>

    <!-- 画像アップロードフォーム -->
    <div class="mt-4 mb-4">
        <h4>新しい画像をアップロード</h4>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="binary_type_id">ファイルの種類を選択</label>
                @foreach ($binaryTypes as $type)
                    <button type="button" class="btn btn-outline-primary" onclick="selectType({{ $type->id }})">{{ $type->type_name_jp }}</button>
                @endforeach
                <input type="hidden" id="binary_type_id" name="binary_type_id" value="">
            </div>
            <div class="form-group">
                <label for="file">ファイルを選択</label>
                <input type="file" class="form-control-file" name="file" id="file" required>
            </div>
            <button type="submit" class="btn btn-primary">アップロード</button>
        </form>
    </div>

    <!-- 画像一覧表示 -->
    <h4>アップロード済みの画像</h4>

    <div class="row">
        @foreach ($imageDataArray as $key => $data)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $data['type'] }}</h5> <!-- 画像の種別を表示 -->
                        <!-- カード内に画像を収めるスタイルを追加 -->
                        <img src="{{ $data['image'] }}" alt="Image" style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function selectType(typeId) {
        document.getElementById('binary_type_id').value = typeId;
    }
</script>

</body>
</html>
