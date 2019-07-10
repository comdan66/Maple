<?php

namespace CRUD\Show\Unit;

abstract class Nomin extends \CRUD\Show\Unit {
  public function __construct($title = null) {
    parent::__construct($title);

    $this->isMin = false;
  }
}