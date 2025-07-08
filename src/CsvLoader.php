<?php

namespace Uranai\Column;

/**
 * CSV読み込み処理クラス
 * コラムデータの読み込みを担当
 */
class CsvLoader
{
    private $config;
    
    public function __construct($config = [])
    {
        $this->config = $config;
    }
    
    /**
     * CSVファイルから記事データを読み込む
     * @return array 記事データ配列
     */
    public function loadArticles()
    {
        $articles = [];
        $csv_file = $this->getCsvFilePath();
        
        if (!file_exists($csv_file)) {
            error_log("CSV file not found: " . $csv_file);
            return $articles;
        }
        
        // CSVファイルをfopenで開く（改行を含むCSVに対応）
        $handle = fopen($csv_file, 'r');
        if ($handle === false) {
            error_log("Failed to open CSV file: " . $csv_file);
            return $articles;
        }
        
        // BOMをスキップ
        $first_byte = fread($handle, 3);
        if ($first_byte !== "\xEF\xBB\xBF") {
            // BOMがない場合は先頭に戻る
            rewind($handle);
        }
        
        // ヘッダー行を読み込み
        $header = fgetcsv($handle);
        if ($header === false) {
            error_log("Failed to read CSV header");
            fclose($handle);
            return $articles;
        }
        
        error_log("CSV header: " . implode(',', $header));
        
        $row_count = 0;
        // データ行を読み込み
        while (($data = fgetcsv($handle)) !== false) {
            $row_count++;
            
            if (count($data) >= 7) {
                $articles[] = [
                    'id' => trim($data[0]),
                    'title' => trim($data[1]),
                    'seo_keywords' => trim($data[2]),
                    'summary' => trim($data[3]),
                    'content' => trim($data[4]),
                    'post_date' => trim($data[5]),
                    'created_date' => trim($data[6])
                ];
            } else {
                error_log("Row $row_count has insufficient data: " . count($data) . " columns, data: " . print_r($data, true));
            }
        }
        
        fclose($handle);
        
        error_log("Loaded " . count($articles) . " articles from " . $row_count . " rows");
        
        // 投稿日時でソート（新しい順）
        usort($articles, function($a, $b) {
            return strcmp($b['post_date'], $a['post_date']);
        });
        
        return $articles;
    }
    
    /**
     * BOM（Byte Order Mark）を除去
     * @param string $content ファイル内容
     * @return string BOM除去後の内容
     */
    private function removeBOM($content)
    {
        // UTF-8 BOM を除去
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }
        
        return $content;
    }
    
    /**
     * CSVファイルのパスを取得
     * @return string CSVファイルパス
     */
    private function getCsvFilePath()
    {
        // 基本的に各サイトのルートディレクトリにcolumn.csvがあることを想定
        $csv_filename = $this->config['csv_filename'] ?? 'column.csv';
        
        // 設定でパスが指定されている場合はそれを使用
        if (isset($this->config['csv_path'])) {
            $csv_path = $this->config['csv_path'];
        } else {
            // 各サイトのルートディレクトリを自動検出
            // common-column-lib/src/CsvLoader.phpから見て、プロジェクトルートは5階層上
            $current_dir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
            $csv_path = $current_dir . '/' . $csv_filename;
        }
        
        // デバッグ用ログ
        error_log("CSV file path: " . $csv_path);
        error_log("CSV file exists: " . (file_exists($csv_path) ? 'Yes' : 'No'));
        
        return $csv_path;
    }
}