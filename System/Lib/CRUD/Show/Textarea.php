<?php

namespace CRUD\Show;

class Textarea extends \CRUD\Show\Unit\Nomin {
  public function val($items) {
    return parent::val(nl2br($items));
  }
}
