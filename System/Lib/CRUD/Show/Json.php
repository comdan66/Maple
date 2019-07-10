<?php

namespace CRUD\Show;

class Json extends \CRUD\Show\Unit\Nomin {
  public function val($items) {
    $this->className('json');
    return parent::val(\HTML\Pre::create(dump(json_decode($items, true)))->attrs(['data-title' => '共有：' . number_format(mb_strlen($items)) . ' 個字元']));
  }
}
