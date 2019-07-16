<?php

namespace CRUD\Table;

class Image extends Images {
  public function val($val) {
    return parent::val([$val]);
  }
}