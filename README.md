# 画像のバイナリ登録システム

## 概要

画像をバイナリデータに変換してデータベースに保存し、再度バイナリデータを画像として表示する機能です。

## 背景

従来、APサーバー上に画像ファイルを直接配置していましたが、以下のようなセキュリティ上の懸念がありました。

- 画像ファイルがサーバー上に直接配置されているため、不正アクセスやファイルの改ざんのリスクが存在する。
- URLの予測やディレクトリトラバーサル攻撃による意図しないファイルアクセスのリスク。

これらのリスクを軽減するため、画像ファイルをバイナリデータに変換し、データベースに保存する方式に変更しました。この方法により、サーバー上に画像ファイルを直接配置する必要がなくなり、セキュリティの強化を図ることができます。

## 主な機能

- 画像のアップロードとバイナリ形式でのデータベースへの保存
- 保存された画像データをデコードして表示
- Laravel Breezeを使用したユーザー認証機能（ログイン、登録、パスワードリセットなど）

## アーキテクチャ

- コントローラ: BinaryFileController
  - ユーザーのリクエストを受け取り、画像のアップロードや表示などの処理を行います。
  - 各種バリデーションとサービス呼び出しのためのロジックを含みます。

- サービス: BinaryFileService
  - 画像のバイナリ変換、データベースへの保存、バイナリデータのデコードなどのビジネスロジックを担当します。
  - BinaryFileControllerから呼び出され、具体的な処理を実行します。

## 必要なツール

- Docker
- Laravel Sail
- Laravel Breeze

## インストールとセットアップ

1. 依存パッケージのインストール

   Dockerがインストールされている環境で、以下のコマンドを実行してください。

./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev

2. Laravel Breezeのインストール

Breezeは既にセットアップされていますが、再度セットアップする場合は以下のコマンドを実行します。

./vendor/bin/sail artisan breeze:install
./vendor/bin/sail artisan migrate

3. 環境ファイルの設定

.env.exampleファイルをコピーして、.envを作成し、必要に応じて環境設定を変更します。

4. データベースのマイグレーション

./vendor/bin/sail artisan migrate

使用方法
画像のアップロード
画像をアップロードすると、バイナリデータに変換されデータベースに保存されます。

画像の表示
保存された画像データは、デコードされて表示されます。
