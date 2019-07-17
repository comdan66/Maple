<?php

namespace CRUD\Table;

class Pagination {
  public static $firstClass  = 'first';
  public static $prevClass   = 'prev';
  public static $activeClass = 'active';
  public static $nextClass   = 'next';
  public static $lastClass   = 'left';
  public static $pageClass   = '';

  public static $firstText = '第一頁';
  public static $lastText  = '最後頁';
  public static $prevText  = '上一頁';
  public static $nextText  = '下一頁';

  public static $limitD4   = 20;
  public static $offsetKey = 'offset';
  public static $limitKey  = 'limit';
  
  public static function info($total, $limit = null, $max = 3) {
    $gets = \Input::get();

    $limitKey  = Pagination::$limitKey;
    $offsetKey = Pagination::$offsetKey;

    !is_numeric($limit)      || $gets[$limitKey] = $limit;
    isset($gets[$limitKey])  || $gets[$limitKey] = Pagination::$limitD4;
    isset($gets[$offsetKey]) || $gets[$offsetKey] = 1;

    if (!($total && ($cnt = (int)ceil($total / $gets[$limitKey])) > 1))
      return ['offset' => ($gets[$offsetKey] - 1) * $gets[$limitKey], 'limit' => $gets[$limitKey], 'links' => []];

    $gets[$offsetKey] = $gets[$offsetKey] + 0;
    $gets[$offsetKey] < 1    && $gets[$offsetKey] = 1;
    $gets[$offsetKey] > $cnt && $gets[$offsetKey] = $cnt;
    $now = $gets[$offsetKey];
    
    $pages = range(1, $cnt);
    $index = array_search($now, $pages);
    $start = $index - $max;
    $start >= 0 || $start = 0;
    
    $pages = array_merge(array_slice($pages, max($index - $max, 0), min($max, $index)), [$pages[$index]], array_slice($pages, $index + 1, $max));

    $prev  = $now !== 1 ? $now - 1 : 0;
    $next  = $now !== $cnt ? $now + 1 : 0;
    $first = reset($pages) !== 1 ? 1 : 0;
    $last  = end($pages) !== $cnt ? $cnt : 0;

    $pages = array_map(function($page) use ($first, $prev, $now, $next, $last) { return ['class' => $page === $now ? Pagination::$activeClass : '', 'offset' => $page, 'text' => $page]; }, $pages);
    $prev  && $pages = array_merge([['class' => Pagination::$prevClass, 'offset' => $prev, 'text' => '']], $pages);
    $first && $pages = array_merge([['class' => Pagination::$firstClass, 'offset' => $first, 'text' => '']], $pages);
    $next  && $pages = array_merge($pages, [['class' => Pagination::$nextClass, 'offset' => $next, 'text' => '']]);
    $last  && $pages = array_merge($pages, [['class' => Pagination::$lastClass, 'offset' => $last, 'text' => '']]);

    return [
      'offset' => ($gets[$offsetKey] - 1) * $gets[$limitKey],
      'limit' => $gets[$limitKey],
      'links' => array_map(function($page) use ($gets, $offsetKey) {
        $gets[$offsetKey] = $page['offset'];
        return '<a href="?' . http_build_query($gets) . '" class="' . implode(' ', array_filter([$page['class'], Pagination::$pageClass], function($t) { return $t !== ''; })) . '">' . $page['text'] . '</a>';
      }, $pages)
    ];
  }
}
