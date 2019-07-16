<?php

namespace CRUD\Show;

class Image extends Images {

  public function val($val) {
    return parent::val([$val]);
  }

  protected function getContent() {
    return parent::getContent();
  }
}