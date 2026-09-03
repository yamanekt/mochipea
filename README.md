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

学校のサーバに ssh で入って git pull し、`php artisan serve` で動かしている。

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

`config:cache` を使っているなら、`.env` を直したあとに消すこと。

```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 起動

```bash
# 外部からアクセスさせるなら host を 0.0.0.0 にする
php artisan serve --host=0.0.0.0 --port=8000
```

### php artisan serve の制約

PHP 内蔵の開発用サーバで、Laravel 公式も本番用途を想定していない。
把握しておくべき点が3つある。

**1. ssh を切ると止まることがある**

前面で起動したまま ssh を抜けると落ちる。生かし続けるなら screen / tmux か nohup を使う。
今どうなっているかはサーバ上で確認できる。

```bash
ps aux | grep -E 'artisan serve|php -S' | grep -v grep   # 何が動いているか
ss -tlnp 2>/dev/null | grep php                          # 待ち受けポート
screen -ls 2>/dev/null; tmux ls 2>/dev/null              # セッションの有無
```

**2. HTTPS にならない**

`artisan serve` は http のみ。Service Worker は https か localhost でしか登録されないため、
**この配置では PWA（ホーム画面に追加・オフライン表示）が実機で機能しない**。
`public/sw.js` と `partials/pwa-head.blade.php` は入っているが無効になる。
`resources/js/make.js` の共有ボタン（`navigator.share` / `navigator.clipboard`）も
セキュアコンテキスト専用なので、同じ理由で動かない。

**3. 同時アクセスに弱い**

既定ではリクエストを1件ずつしか捌けない。重いページを開いている間、他の人の操作が待たされる。
並列数は環境変数で増やせる。

```bash
PHP_CLI_SERVER_WORKERS=4 php artisan serve --host=0.0.0.0 --port=8000
```

### 未確定（サーバで確認して埋めること）

- ssh のホスト名と配置先ディレクトリ
- **実際の URL**。`ホスト:ポート` の直下なのか、`/~sys1_26_hijyo/` のように
  Apache の UserDir やリバースプロキシ越しなのか。ブラウザのアドレスバーを見れば分かる
- 常時起動しているのか、見せるときだけ起動しているのか

### サブディレクトリ配置に備えた作り

ドメイン直下でない場所に置かれても壊れないよう、以下は相対パスか `asset()` 経由にしてある。
`ホスト:ポート` の直下で動かす場合でも問題なく動くので、そのままでよい。

- `public/manifest.json` の `start_url` / `scope`
- `public/sw.js` の `OFFLINE_URL` とキャッシュ判定
- `partials/pwa-head.blade.php` の manifest / favicon / apple-touch-icon

ブラウザが暗黙に探す `/favicon.ico` はドメイン直下しか見ないため、
サブディレクトリ配置だと当たらない。`<link rel="icon">` で明示しているのはそのため。
