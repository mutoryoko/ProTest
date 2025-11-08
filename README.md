# 新COACHTECH フリマ（機能追加）

## 概要
本アプリは、ユーザーが商品を出品・購入できるフリマアプリです。
#### 主な機能
<ul>
	<li>ユーザー登録・ログイン（メール認証対応）</li>
	<li>商品一覧・検索機能</li>
	<li>いいね・コメント機能</li>
	<li>商品の出品（画像アップロード、カテゴリー・商品の状態設定、価格入力等）</li>
	<li>商品購入機能（Stripeを利用した決済）</li>
	<li>マイページ機能（出品商品、購入商品一覧の確認）</li>
	<li>【NEW!】商品購入後の取引チャット機能（編集・削除可能）</li>
	<li>【NEW!】取引評価機能</li>
	<li>【NEW!】取引完了のメール送信機能</li>
</ul>

#### 実行環境
<ul>
	<li>Laravel Framework: 9.52.20</li>
	<li>PHP: 8.1</li>
	<li>nginx: 1.21.1</li>
	<li>mysql: 8.0.26</li>
	<li>mail: Mailhog</li>
</ul>

#### URL
<ul>
	<li>開発環境: <a href="http://localhost">http://localhost</a> </li>
	<li>phpmyadmin: <a href="http://localhost:8080">http://localhost:8080</a> </li>
	<li>Mailhog: <a href="http://localhost:8025">http://localhost:8025</a></li>
</ul>

#### ER図
<img src="ER.drawio.svg" width=85% />

&nbsp;

## Dockerビルド
```
git clone git@github.com:mutoryoko/ProTest.git
docker compose up -d --build
```
&nbsp;
## Laravel環境構築
```
docker compose exec php bash
composer install
cp .env.example .env
```

### メールの設定

メール機能はMailhogを使用。<br />
.envファイルのMAIL_FROM_ADDRESSは任意のアドレスに変更可。

### Stripeの設定

決済機能はStripeを使用。Stripeのアカウント作成後、.envファイルに設定を追加する。
```
STRIPE_KEY=テスト用の公開可能キー
STRIPE_SECRET_KEY=テスト用のシークレットキー
```
&nbsp;

.envファイルを編集後、以下のコマンドを実行。
```
php artisan key:generate
php artisan migrate
php artisan db:seed
```

<details><summary>テスト環境構築</summary>

```
docker compose exec mysql bash
mysql -u root -p
```
パスワード:rootを入力してMySQLコンテナ内に入る。
```
CREATE DATABASE demo_test;
```
データベースができたら、MySQLコンテナを抜ける。
```
docker compose exec php bash
cp .env .env.testing
```
.env.testingファイルのAPP_ENV、APP_KEYを以下に変更。
```
APP_ENV=test
APP_KEY=
```
.env.testingファイルのデータベース情報を以下に変更。
```
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```
.env.testingを編集後、以下のコマンドを実行。
```
php artisan key:generate --env=testing
php artisan config:clear
php artisan migrate --env=testing
```
テスト実行。
```
php artisan test
```
</details>
&nbsp;

## Seederファイルについて
UsersTableSeederには以下の3名が登録されている。

| ユーザー名 | メールアドレス | パスワード |
| :---: | :---: | :---: |
| 鈴木一郎 | ichiro@seeder.com | password1 |
| 佐藤二郎 | jiro@seeder.com | password2 |
| 北島三郎 | saburo@seeder.com | password3 |

デフォルトで各ユーザーが出品している商品は以下の通り。
| ユーザー名 | 出品した商品 |
| :---: | :---: |
| 鈴木一郎 | 腕時計・HDD・玉ねぎ3束・革靴・ノートPC |
| 佐藤二郎 | マイク・ショルダーバッグ・タンブラー・コーヒーミル・メイクセット |
| 北島三郎 | なし |

&nbsp;

## 本アプリに関する注意事項
+ 商品購入画面で支払い方法の選択して購入する際、Stripeの仕様上、<br />支払い方法の選択によって購入処理の流れが異なります。<br />

	- 「カード支払い」の場合：
	1. 購入ボタンを押す。
	2. Stripeの決済画面へ遷移し、支払うボタンを押す。
	3. 購入処理が実行され、購入済みとなる。（DBに登録）

	- 「コンビニ支払い」の場合：
	1. 購入ボタンを押す。
	2. 購入処理が実行され、購入済みとなる。（DBに登録）
	3. Stripeの画面へと遷移。（その後ユーザーが戻るボタンで戻るか、ブラウザを閉じる）

+ 取引チャット機能は、ユーザーが商品を購入した時または<br />ユーザーの出品している商品が購入された時に「取引中の商品」タブに表示されます。
+ 取引チャットの編集はメッセージのみで、投稿した画像の変更はできません。
