<?php

namespace Uranai\Column;

/**
 * コラム管理の核となるクラス
 * 100サイトで共通使用される機能を提供
 */
class ColumnManager
{
    private $csvLoader;
    private $articleRenderer;
    private $paginationHelper;
    private $config;
    
    public function __construct($config = [])
    {
        $this->config = array_merge([
            'per_page' => 50,
            'csv_filename' => 'column.csv',
            'template_name' => 'column_list'
        ], $config);
        
        $this->csvLoader = new CsvLoader($this->config);
        $this->articleRenderer = new ArticleRenderer($this->config);
        $this->paginationHelper = new PaginationHelper($this->config);
    }
    
    /**
     * コラム一覧を取得・表示
     * @param array $get_data リクエストデータ
     * @return array 表示用データ
     */
    public function getColumnList($get_data = [])
    {
        // 記事データ読み込み
        $articles = $this->csvLoader->loadArticles();
        
        // ページネーション処理
        $page = isset($get_data['page']) ? (int)$get_data['page'] : 1;
        $pagination_data = $this->paginationHelper->paginate($articles, $page);
        
        // 表示データ作成
        $disp_array = [
            'articles' => $pagination_data['page_articles'],
            'current_page' => $pagination_data['current_page'],
            'total_pages' => $pagination_data['total_pages'],
            'total_articles' => $pagination_data['total_articles']
        ];
        
        return $disp_array;
    }
    
    /**
     * 設定値を取得
     * @param string $key 設定キー
     * @return mixed 設定値
     */
    public function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        }
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }
    
    /**
     * 設定値を更新
     * @param string $key 設定キー
     * @param mixed $value 設定値
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }
}