<?php

namespace CRUD\Table;

class Number extends Unit {
  private $unit = null;
  private $decimal = 0;

  public function unit(string $unit) {
    $this->unit = $unit;
    return $this;
  }

  public function decimal(int $decimal) {
    $this->decimal = $decimal;
    return $this;
  }

  public function val($val) {
    is_array($val) && $val = count($val);

    parent::val(is_numeric($val)
      ? '<div class="number"' . ($this->unit !== null ? ' data-unit="' . $this->unit . '"' : '') . '>' . number_format($val, $this->decimal) . '</div>'
      : '');
    return $this;
  }
}