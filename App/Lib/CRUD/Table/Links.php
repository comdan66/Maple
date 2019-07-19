<?php

namespace CRUD\Table;

class Links extends Unit {
  public function val($val) {
    parent::val($val
      ? '<div class="links">' . implode('', array_map(function($t) {
          if (is_array($t)) return '<a href="' . array_shift($t) . '">' . array_shift($t) . '</a>';
          if ($t instanceof \HTML\A) return $t;
          return '<a href="' . $t . '">' . $t . '</a>';
        }, $val)) . '</div>'
      : '');
    return $this;
  }
}