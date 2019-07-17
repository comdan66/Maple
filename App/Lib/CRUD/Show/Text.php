<?php

namespace CRUD\Show;

class Text extends Unit {

  public function val($val) {
    return parent::val('' . $val);
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-text">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }
}
