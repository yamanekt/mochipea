# mochipea

## 構成

```
mochipea/
├── Dockerfile
├── docker-compose.yml
├── nginx.conf
└── src/
    ├── app/
    ├── database/
    │   └── migrations/
    ├── .env.example
    └── ...
```

| サービス | 内容 |
|----------|------|
| app | PHP 8.4 / Laravel |
| web | Nginx |
| db | MySQL 8.0 |

---

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

### 7. 確認

http://localhost:8080 にアクセスしてLaravelのトップが表示されればOK

---

## 開発するとき

### コンテナ起動

```bash
docker compose up -d
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
