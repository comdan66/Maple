<?php

namespace CRUD\Table;

class Items extends Unit {
  public function val($val) {
    parent::val(implode('', array_map(function($t) { return '<span>' . $t . '</span>'; }, $val)));
    return $this->className('items');
  }
}