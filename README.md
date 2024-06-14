# 空欄フィールドの簡易入力画面
このライブラリは、PHPで書かれたメソッドからデータベースのテーブルで空欄となっているフィールドへ簡単に登録する手段を提供します。
## インストール方法
パッケージを変更しないならば、このソースコードは必要ありません。<br />
パッケージを使用したいだけならば、下記ファイルにアクセスしてください。
```
index.php
```
## 動作環境
PHP 7.1～

このメソッドはHTMLとPHPからJavascriptのjQueryライブラリを利用してサーバーへデータ送信するメソッドです。
## 使い方
最初にDatabaseに接続するための情報を登録してください。<br>
また、空欄を埋めたいフィールド名の設定も行います。
```php
$host = 'HostName Or Host IP Address';
$db   = 'Database Name';
$user = 'Database Access User Name';
$pass = 'Database Access Password';
$table = 'Table Name';
$field_name = 'Field Name';
$charset = 'Database Charset (utf-8, utf8mb4, iso-2022-jp...etc)';
```

### タイムアウトについて
通信をする実行環境の通信速度によってはHTTP通信時にタイムアウトが発生する可能性があります。<br />
何度も同じような現象が起こる際は、サーバーの接続の調整もしくは`HTTPクライアントの明示的な指定`からHTTPクライアントの指定及びタイムアウトの時間を増やして、再度実行してください。

### 使用サイト
リユースドメイン [reusedomain.com](https://reusedomain.com)
