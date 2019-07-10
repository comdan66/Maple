<?php

namespace CRUD\Show;

class Texts extends \CRUD\Show\Unit\Nomin {
  public function val($items) {
    parent::val(implode('', array_map(function($item) {
      return '<span>' . $item . '</span>';
    }, $items)));
    return $this->className('texts');
  }
}