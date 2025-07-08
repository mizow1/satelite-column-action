<?php

namespace Uranai\Column;

/**
 * ページネーション処理クラス
 * 記事の分割表示を担当
 */
class PaginationHelper
{
    private $config;
    
    public function __construct($config = [])
    {
        $this->config = $config;
    }
    
    /**
     * 記事配列をページネーションで分割
     * @param array $articles 記事データ配列
     * @param int $page 現在のページ番号
     * @return array ページネーション結果
     */
    public function paginate($articles, $page = 1)
    {
        $per_page = $this->config['per_page'] ?? 50;
        $total_articles = count($articles);
        $total_pages = ceil($total_articles / $per_page);
        
        // ページ番号の調整
        $page = max(1, min($page, $total_pages));
        
        $offset = ($page - 1) * $per_page;
        $page_articles = array_slice($articles, $offset, $per_page);
        
        return [
            'page_articles' => $page_articles,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_articles' => $total_articles,
            'per_page' => $per_page,
            'offset' => $offset
        ];
    }
    
    /**
     * ページネーションリンクを生成
     * @param int $current_page 現在のページ
     * @param int $total_pages 総ページ数
     * @param string $base_url ベースURL
     * @param int $range 表示範囲
     * @return array ページネーションリンク配列
     */
    public function generatePaginationLinks($current_page, $total_pages, $base_url = '', $range = 5)
    {
        $links = [];
        
        // 前のページ
        if ($current_page > 1) {
            $links['prev'] = $base_url . '?page=' . ($current_page - 1);
        }
        
        // ページ番号リンク
        $start = max(1, $current_page - $range);
        $end = min($total_pages, $current_page + $range);
        
        for ($i = $start; $i <= $end; $i++) {
            $links['pages'][$i] = [
                'url' => $base_url . '?page=' . $i,
                'current' => ($i == $current_page)
            ];
        }
        
        // 次のページ
        if ($current_page < $total_pages) {
            $links['next'] = $base_url . '?page=' . ($current_page + 1);
        }
        
        return $links;
    }
}