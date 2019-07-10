<?php

namespace CRUD\Show;

class Links extends \CRUD\Show\Unit\Nomin {
  public function val($items) {
    parent::val(implode('', array_map(function($t) {
      if (is_array($t))
        return \HTML\A::create(array_shift($t))->href(array_shift($t));
      
      if ($t instanceof \HTML\A)
        return $t;

      return \HTML\A::create($t)->href($t);
    }, $items)));

    return $this->className('links');
  }
}