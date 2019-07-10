<?php

namespace CRUD\Table;

class Images extends Unit {
  public function val($vals) {
    parent::val(implode('', array_filter(array_map(function($val) {
      $val instanceof \_M\ImageUploader && $val = $val->url();
      return $val ? '<img src="' . $val . '" />' : '';
    }, $vals))));

    return $this->className('oaips')->width(50);
  }
}