# mochipea

## 初期設定

### 1. クローン

```bash
git clone <リポジトリURL>
cd mochipea
```

### 2. コンテナ起動

```bash
docker compose up -d --build
```

### 3. パッケージインストール

```bash
docker compose exec app composer install
```

### 4. `.env` を作成

```bash
cp src/.env.example src/.env
```

### 5. アプリキー生成

```bash
docker compose exec app php artisan key:generate
```

### 6. マイグレーション

```bash
docker compose exec app php artisan migrate
```

### 7. npmのインストール

```bash
cd src
npm install
```

### 8. npmの起動

```bash
npm run dev
```

### 9. 確認

http://localhost:8080 にアクセスしてLaravelのトップが表示されればOK

---

## 開発するとき

### コンテナ起動

```bash
docker compose up -d
```

### コンテナ起動

```bash
cd src
npm run dev
```

### コンテナ停止

```bash
docker compose down
```

### ブラウザで確認

http://localhost:8080

### DBに入る

```bash
docker compose exec db mysql -u user -p
# パスワード: password
```

### コードを最新にする

```bash
git pull
docker compose exec app php artisan migrate
```

---

## コマンド一覧

## git
<dl>
 <dt>メインにpullする</dt>
 <dd>git pull origin main</dd>
 <dt>ブランチを分けて作業する</dt>
 <dd>git checkout ブランチ名</dd>
 <dt>作業終了後にプッシュする</dt>
 <dd></dd>git add .<br>
 git commit -m "コミット内容"<br>
 git push origin ブランチ名</dd>
</dl>

```bash
# マイグレーション実行
docker compose exec app php artisan migrate

# マイグレーションをリセット
docker compose exec app php artisan migrate:fresh

# ルート確認
docker compose exec app php artisan route:list

# キャッシュクリア
docker compose exec app php artisan config:clear

# ログ確認
docker compose logs -f

# パッケージ追加
docker compose exec app composer require <パッケージ名>
```

---

## デプロイ

学校のサーバに ssh で入って git pull する。

```bash
ssh <サーバ>
cd <配置先ディレクトリ>
git pull origin main
```

pull したあと、何が変わったかを見れば下の表のどれが必要か分かる。

```bash
git diff --name-only HEAD@{1} HEAD
```

### git pull だけで済まないとき

`public/build`（Vite の成果物）と `vendor` と `.env` は **git に入っていない**（`src/.gitignore` 参照）。
何を変えたかによって、pull のあとに追加作業が要る。

| 変えたもの | 追加で必要なこと |
|---|---|
| blade / `public/` 直下のファイル | なし。git pull だけで反映される |
| `resources/css` `resources/js` | `npm run build` |
| `composer.json` / `composer.lock` | `composer install` |
| `database/migrations` | `php artisan migrate` |
| `.env` の項目 | サーバ側の `.env` を手で直す（git には乗らない） |

Laravel 側でキャッシュしているなら、pull 後に消す。

```bash
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

### サブディレクトリ配置についての注意

ドメイン直下ではなく `/~sys1_26_hijyo/` のような場所に置いている。
そのため以下はすべて相対パスか `asset()` 経由にしてある。絶対パスに書き換えると本番でリンクが外れる。

- `public/manifest.json` の `start_url` / `scope`
- `public/sw.js` の `OFFLINE_URL` とキャッシュ判定
- `partials/pwa-head.blade.php` の manifest / favicon / apple-touch-icon

ブラウザが暗黙に探しにいく `/favicon.ico` は**ドメイン直下**を見るのでこの配置では当たらない。
`<link rel="icon">` で明示しているのはそのため。
