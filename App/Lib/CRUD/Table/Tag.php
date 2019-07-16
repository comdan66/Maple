<?php

namespace CRUD\Table;

class Tag extends Unit {
  const YELLOW = 'yellow';
  const BLUE   = 'blue';
  const GREEN  = 'green';
  const GRAY   = 'gray';
  const RED    = 'red';

  private $color = '';

  public function color($color) {
    $this->color = $color;
    return $this;
  }

  public function val($val) {
    $val instanceof \M\Model && $val = $val->id ?? '';
    parent::val($val !== ''
      ? '<span class="tag' . ($this->color ? ' ' . $this->color : '') . '">' . $val . '</span>'
      : '');
    return $this;
  }
}