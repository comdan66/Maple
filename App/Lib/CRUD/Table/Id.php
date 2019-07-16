<?php

namespace CRUD\Table;

class Id extends Unit {

  public function val($val = null) {
    $val = $val ?? $this->obj;
    $val instanceof \M\Model && $val = $val->id ?? '';
    parent::val($val !== '' ? '<div class="id">' . $val . '</div>' : '');
    return $this;
  }

  public static function create($title = null) {
    $obj = new static('ID');
    return $obj->width(80)->order('id')->val();
  }
}