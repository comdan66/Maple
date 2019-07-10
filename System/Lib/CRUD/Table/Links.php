<?php

namespace CRUD\Table;

class Links extends Unit {
  public function val($val) {
    parent::val(implode('', array_map(function($t) {
      if (is_array($t))
        return '<a href="' . array_shift($t) . '">' . array_shift($t) . '</a>';
    
      if ($t instanceof \HTML\A)
        return $t;

      return \HTML\A::create($t)->href(\HTML\A::create($t));
    }, $val)));
    return $this->className('links');
  }
}