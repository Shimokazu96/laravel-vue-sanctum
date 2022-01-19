## 概要
Laravel8(Sanctum) + Vue.js3の環境構築

## バージョン

|名称|バージョン|
|:--:|:--:|
|Nginx|1.19|
|MySQL|8.0|
|PHP|8.0|
|Laravel|8.x|
|Node.js|16.13.2|
|Vue.js|3.x|
|Composer|2.0.14|

## .env作成
- `.env.example`をコピーして`.env`を作成して各項目に値を定義する。
- `docker-compose config`で`.env`に設定した環境変数が`docker-compose.yml`にセットされているか確認する。

## src/.env作成

```
$ cd src
$ cp .env.example .env
```
DB_HOSTはmysqlコンテナのサービス名を指定する

## ビルド＆コンテナ起動

```
$ docker-compose up -d --build
```

## Package Install

appコンテナに入る

```
$ docker-compose exec app bash
```

以降は全てappコンテナ内で実行

```
composer install
php artisan key:generate
npm install
```

- Vue-Router（4.x）
- Vuex（4.x）

もインストールされる。


## Vue.jsのビルド

appコンテナ内で実行

```
npm run dev
```


## browerSync起動

appコンテナ内で実行

```
npm run watch
```

## Docker Compose Command

```
イメージをビルド
$ docker-compose build
コンテナ起動
$ docker-compose up -d
イメージをビルド＋コンテナ起動
$ docker-compose up -d --build
コンテナ終了（削除）
$ docker-compose down
コンテナ再起動
$ docker-compose restart
```

### URL

- トップページ：http://localhost
- API：http://localhost/api
- mailhog：http://localhost:8025/

### Composer 追加パッケージ

- [laravel/sanctum](https://github.com/laravel/sanctum)
- [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)
