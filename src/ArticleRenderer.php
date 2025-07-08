<?php

namespace Uranai\Column;

/**
 * 記事表示処理クラス
 * テンプレートレンダリングを担当
 */
class ArticleRenderer
{
    private $config;
    
    public function __construct($config = [])
    {
        $this->config = $config;
    }
    
    /**
     * 記事の概要を安全に取得
     * @param string $content 記事内容
     * @param int $length 概要の長さ
     * @return string 概要文
     */
    public function generateSummary($content, $length = 200)
    {
        // HTMLタグを除去
        $text = strip_tags($content);
        
        // 改行を除去
        $text = preg_replace('/\s+/', ' ', $text);
        
        // 指定の長さで切り取り
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length) . '...';
        }
        
        return trim($text);
    }
    
    /**
     * 記事の日時を整形
     * @param string $date_string 日時文字列
     * @param string $format 出力フォーマット
     * @return string 整形された日時
     */
    public function formatDate($date_string, $format = 'Y年m月d日')
    {
        $timestamp = strtotime($date_string);
        if ($timestamp === false) {
            return $date_string;
        }
        
        return date($format, $timestamp);
    }
    
    /**
     * SEOキーワードを配列に変換
     * @param string $keywords カンマ区切りキーワード
     * @return array キーワード配列
     */
    public function parseKeywords($keywords)
    {
        if (empty($keywords)) {
            return [];
        }
        
        $keyword_array = explode(',', $keywords);
        return array_map('trim', $keyword_array);
    }
    
    /**
     * 記事URLを生成
     * @param array $article 記事データ
     * @param string $base_url ベースURL
     * @return string 記事URL
     */
    public function generateArticleUrl($article, $base_url = '')
    {
        // 基本的なURL生成ロジック
        $article_id = $article['id'];
        return $base_url . '/column/detail/' . $article_id;
    }
    
    /**
     * 記事データを表示用に整形
     * @param array $article 記事データ
     * @return array 整形された記事データ
     */
    public function formatArticleForDisplay($article)
    {
        return [
            'id' => $article['id'],
            'title' => htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'),
            'summary' => $this->generateSummary($article['content'], 150),
            'formatted_date' => $this->formatDate($article['post_date']),
            'keywords' => $this->parseKeywords($article['seo_keywords']),
            'url' => $this->generateArticleUrl($article)
        ];
    }
}