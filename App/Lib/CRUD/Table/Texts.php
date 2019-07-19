<?php

namespace CRUD\Table;

class Texts extends Unit {
  public function val($val) {
    parent::val($val
      ? '<div class="texts">' . implode('', array_map(function($t) {
        return '<span>' . $t . '</span>';
      }, $val)) . '</div>'
      : '');
    return $this;
  }
}