<?php

namespace CRUD\Show;

class Ckeditor extends \CRUD\Show\Unit\Nomin {
  public function val($items) {
    parent::val($items);
    return $this->className('ckeditor');
  }
}