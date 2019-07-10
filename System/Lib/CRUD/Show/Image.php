<?php

namespace CRUD\Show;

class Image extends Medias {
  public function val($src) {
    return parent::val([$src]);
  }
}