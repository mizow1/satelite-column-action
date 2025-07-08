# 共通コラム機能ライブラリ

100サイトで共通使用できるコラム機能ライブラリです。

## 特徴

- **1箇所にコードを集約**: 共有ライブラリで一元管理
- **自動取り込み**: Composerで簡単インストール
- **ワンコマンド更新**: `composer update`で100サイト一括更新

## インストール方法

### 1. Composerでインストール

```bash
composer require uranai/common-column-lib
```

### 2. 手動インストール（Composerが使えない場合）

```bash
git clone [リポジトリURL] common-column-lib
```

## 使用方法

### 基本的な使用例

```php
<?php
require_once 'vendor/autoload.php';

use Uranai\Column\ColumnManager;

// コラムマネージャー初期化
$columnManager = new ColumnManager([
    'per_page' => 50,
    'csv_filename' => 'column.csv'
]);

// コラム一覧取得
$disp_array = $columnManager->getColumnList($_GET);

// テンプレートに渡す
$this->display($disp_array, 'column_list');
```

### 既存のColumnActionを置き換え

```php
<?php
require_once 'vendor/autoload.php';
require_once(MODEL.'front/controller/AbstractControllerClass.php');

use Uranai\Column\ColumnManager;

class ColumnAction extends AbstractController
{
    private $columnManager;
    
    function __construct($controller='',$action='',&$session_data=array(),$device='')
    {
        $this->init($controller,$action,$session_data,$device);
        $this->columnManager = new ColumnManager();
    }
    
    function Execute($get_data=array(),&$session_data=array())
    {
        $disp_array = $this->columnManager->getColumnList($get_data);
        $this->display($disp_array, 'column_list');
    }
}
```

## 設定

### 設定ファイル

`config/column_config.php`で設定をカスタマイズできます：

```php
return [
    'per_page' => 50,
    'csv_filename' => 'column.csv',
    'date_format' => 'Y年m月d日',
    // その他設定...
];
```

### サイト固有設定

各サイトで異なる設定が必要な場合：

```php
$columnManager = new ColumnManager([
    'per_page' => 30,  // サイトAは30件表示
    'date_format' => 'Y/m/d'  // 日付フォーマット変更
]);
```

## 更新手順

### 1. ライブラリ更新

```bash
git tag v1.1.0
git push origin v1.1.0
```

### 2. 各サイトに反映

```bash
# 各サイトで実行
composer update uranai/common-column-lib
```

### 3. 自動更新（GitHub Actions使用）

```yaml
# .github/workflows/deploy.yml
name: Deploy to All Sites
on:
  push:
    tags:
      - 'v*'
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Update all sites
        run: |
          # 100サイトに順次SSH接続してcomposer update実行
          for site in site1 site2 site3 ... site100; do
            ssh $site "cd /var/www && composer update uranai/common-column-lib"
          done
```

## ファイル構成

```
common-column-lib/
├── composer.json
├── README.md
├── src/
│   ├── ColumnManager.php      # メインクラス
│   ├── CsvLoader.php          # CSV読み込み
│   ├── ArticleRenderer.php    # 記事表示処理
│   └── PaginationHelper.php   # ページネーション
└── config/
    └── column_config.php      # 設定ファイル
```

## トラブルシューティング

### CSVファイルが見つからない場合

```php
$columnManager->setConfig('csv_path', '/path/to/column.csv');
```

### ページネーションがおかしい場合

```php
$columnManager->setConfig('per_page', 20);
```

## バージョン管理

- v1.0.0: 初期版
- v1.1.0: 機能追加予定

## ライセンス

MIT License