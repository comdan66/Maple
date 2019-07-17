<?php

namespace CRUD\Show;

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
    return parent::val('' . $val);
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-tag">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div><span class="' . $this->color . '">' . $this->val . '</span></div>';
    $return .= '</div>';
    return $return;
  }
}
