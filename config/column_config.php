<?php

/**
 * コラム機能設定ファイル
 * 各サイトでカスタマイズ可能な設定値
 */

return [
    // ページネーション設定
    'per_page' => 50,
    
    // CSVファイル設定
    'csv_filename' => 'column.csv',
    'csv_path' => null, // nullの場合は自動検出
    
    // テンプレート設定
    'template_name' => 'column_list',
    
    // 記事表示設定
    'summary_length' => 200,
    'date_format' => 'Y年m月d日',
    
    // URL設定
    'base_url' => '',
    'article_url_pattern' => '/column/detail/{id}',
    
    // デバッグ設定
    'debug_mode' => false,
    'log_enabled' => true,
    
    // キャッシュ設定
    'cache_enabled' => false,
    'cache_duration' => 3600,
    
    // サイト固有設定（各サイトで上書き可能）
    'site_specific' => [
        // 例：サイトAの設定
        // 'site_a' => [
        //     'per_page' => 30,
        //     'date_format' => 'Y/m/d'
        // ]
    ]
];