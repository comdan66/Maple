<?php

namespace CRUD\Table;

class Images extends Unit {
  public static function create(string $title) {
    $obj = new static($title);
    return $obj->width(60);
  }

  public function val($vals) {
    parent::val(implode('', array_filter(array_map(function($val) {
      $val instanceof \_M\ImageUploader && $val = $val->url();
      return $val ? '<img src="' . $val . '" />' : '';
    }, $vals))));
    return $this->class('oaips');
  }
}