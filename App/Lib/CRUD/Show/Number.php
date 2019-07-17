<?php

namespace CRUD\Show;

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
    return parent::val('' . $val);
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-number">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . '<span>' . number_format($this->val, $this->decimal) . '</span>' . ($this->unit !== null ? '<span>' . $this->unit . '</span>' : '') . '</div>';
    $return .= '</div>';
    return $return;
  }
}
