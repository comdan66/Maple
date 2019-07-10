<?php

namespace CRUD;

class Table extends \CRUD {
  const SORT_KEY = '_s';
  const SEARCH_KEY = '_q';

  private $model, $option = [], $url, $addUrl, $sortUrl, $where, $runQuery = false, $total = 0, $objs = [], $trsIndex = 0, $trs = [], $searches = [], $searchStr, $tableStr, $pagesStr;

  public static function create($model, $option = []) {
    return new static($model, $option);
  }

  public function __construct($model, $option = []) {
    $this->model = $model;
    $this->option = $option;

    $this->option instanceof \Where && $this->option = ['where' => $this->option];
    $this->setWhere(isset($this->option['where']) ? $this->option['where'] : \Where::create());
  }

  public function &objs() {
    return $this->query()->objs;
  }

  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  public function setAddUrl($addUrl) {
    $this->addUrl = $addUrl;
    return $this;
  }

  public function setSortUrl($sortUrl) {
    $this->sortUrl = $sortUrl;
    return $this;
  }

  public function setWhere($where) {
    $this->where = $where instanceof \Where ? $where : \Where::create($where);
    return $this;
  }

  private function query() {
    if($this->runQuery)
      return $this;

    \CRUD\Table\Pagination::$firstText   = '';
    \CRUD\Table\Pagination::$lastText    = '';
    \CRUD\Table\Pagination::$prevText    = '';
    \CRUD\Table\Pagination::$nextText    = '';

    $this->runQuery = true;

    $model = $this->model;
    $this->total = $model::count($this->where);
    $this->pagesStr = \CRUD\Table\Pagination::info($this->total);
    unset($this->option['where']);

    $this->objs  = $model::all(array_merge([
     'order'  => \CRUD\Table\Order::desc('id'),
     'offset' => $this->pagesStr['offset'],
     'limit'  => $this->pagesStr['limit'],
     'where'  => $this->where], $this->option));

    $this->pagesStr = '<div class="pagination"><div>' . implode('', $this->pagesStr['links']) . '</div></div>';

    return $this;
  }

  public function search($closure) {
    if ($this->searchStr !== null)
      return $this->searchStr;

    $title = null;
    $closure($title);
    $title == null && $title = '';
    
    $gets = \Input::get();
    $titles = [];

    foreach ($this->searches as $search) {
      unset($gets[$search->key()]);

      if (!$where = $search->updateSql(\Input::get($search->key(), true)))
        continue;

      $this->where->and($where);
      array_push($titles, $search->title());
    }

    $this->query();

    $gets = http_build_query($gets);
    $gets && $gets = '?' . $gets;
    $cancel = \Url::current() . $gets;

    $sortKey = '';

    if ($this->sortUrl) {
      $gets = \Input::get();

      if (isset($gets[\CRUD\Table\Order::KEY]))
        unset($gets[\CRUD\Table\Order::KEY]);

      foreach (array_keys($this->searches) as $key)
        if (isset($gets[$key]))
          unset($gets[$key]);
  
      if (isset($gets[Table::SORT_KEY]) && $gets[Table::SORT_KEY] === 'true') {
        $ing = false;
        unset($gets[Table::SORT_KEY]);
      } else {
        $ing = true;
        $gets[Table::SORT_KEY] = 'true';
      }

      $gets = http_build_query($gets);
      $gets && $gets = '?' . $gets;
      $sortKey = \Url::current() . $gets;
    }

    $this->searchStr = '';

    $this->searchStr .= $title ? '<span class="title">' . $title . '</span>' : '';
    $this->searchStr .= '<form class="search" action="' . \Url::current() . '" method="get">';
      $this->searchStr .= '<div class="info' . ($titles ? ' show' : '') . '">';
        $this->searchStr .= '<a class="conditions-btn"></a>';
        $this->searchStr .= '<span>' . ($this->url ? $this->url : '') . ($this->addUrl ? '<a href="' . $this->addUrl . '" class="add">新增</a>' : '') . ($sortKey ? '<a href="' . $sortKey . '" class="' . ($ing ? 'sort' : 'finish') . '">' . ($ing ? '排序' : '完成') . '</a>' : '') . '</span>';
        $this->searchStr .= '<span>' . ($titles ? '您針對' . implode('、', array_map(function($title) { return '「' . $title . '」'; }, $titles)) . '搜尋，結果' : '目前全部') . '共有「' . number_format($this->total) . '」筆。' . '</span>';
      $this->searchStr .= '</div>';
      $this->searchStr .= '<div class="conditions">';
        $this->searchStr .= implode('', $this->searches);

        $this->searchStr .= '<div class="btns">';
          $this->searchStr .= '<button type="submit">搜尋</button>';
          $this->searchStr .= '<a href="' . $cancel . '">取消</a>';
        $this->searchStr .= '</div>';

      $this->searchStr .= '</div>';
    $this->searchStr .= '</form>';

    $this->searches = [];
    return $this->searchStr;
  }

  public function list($closure) {
    if ($this->tableStr !== null)
      return $this->tableStr;

    $this->trs = [];

    $this->query();
    
    $title = null;
    foreach ($this->objs as $i => $obj) {
      $this->trsIndex = $i;
      $closure($obj, $title);
    }
    $title == null && $title = '';

    \Input::get(Table::SORT_KEY) === 'true' || $this->sortUrl = '';

    $this->tableStr = '';

    $this->tableStr .= $title ? '<span class="title">' . $title . '</span>' : '';
    $this->tableStr .= '<div class="panel">';
      $this->tableStr .= $this->sortUrl ? '<table class="list dragable" data-sorturl="' . $this->sortUrl . '">' : '<table class="list">';

        $this->tableStr .= '<thead>';
          $this->tableStr .= '<tr>';
          $this->tableStr .= $this->trs ? implode('', array_map(function($tr) { return $tr->thString($this->sortUrl); }, $this->trs[0])) : '';
          $this->tableStr .= '</tr>';
        $this->tableStr .= '</thead>';
        $this->tableStr .= '<tbody>';

          $this->tableStr .= $this->trs ? implode('', array_map(function($tds) {
            return ($this->sortUrl && $tds[0]->getObj() && isset($tds[0]->getObj()->id, $tds[0]->getObj()->sort) ? '<tr data-id="' . $tds[0]->getObj()->id . '" data-sort="' . $tds[0]->getObj()->sort . '">' : '<tr>') . implode('', $tds) . '</tr>';
          }, $this->trs)) : '<tr><td colspan></td></tr>';

        $this->tableStr .= '</tbody>';
      $this->tableStr .= '</table>';
    $this->tableStr .= '</div>';
    
    $this->trs = [];
    $this->objs = [];

    return $this->tableStr;
  }

  public function pages() {
    $this->pagesStr !== null || $this->query();
    return $this->pagesStr;
  }

  public function appendSearch(\CRUD\Table\Search $search) {
    array_push($this->searches, $search->key(Table::SEARCH_KEY . count($this->searches)));
    return $this;
  }

  public function appendUnit(\CRUD\Table\Unit $unit) {
    if (!isset($this->trs[$this->trsIndex])) {
      $this->trs[$this->trsIndex] = [];

      \Input::get(Table::SORT_KEY) === 'true' || $this->sortUrl = '';
      $this->sortUrl && array_push($this->trs[$this->trsIndex], \CRUD\Table\Sort::create('排序')->width(44)->className('cente')->val('<span class="drag"></span>'));
    }

    array_push($this->trs[$this->trsIndex], $unit);
    return $this;
  }
}
