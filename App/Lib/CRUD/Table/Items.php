<?php

namespace CRUD\Table;

class Items extends Unit {
  public function val($val) {
    parent::val($val
      ? '<div class="items">' . implode('', array_map(function($t) {
          return '<span>' . $t . '</span>';
        }, $val)) . '</div>'
      : '');
    return $this;
  }
}