<?php

namespace CRUD\Show;

class Text extends Unit {
  public function isMin($isMin = true) {
    $this->isMin = $isMin;
    return $this;
  }
}
