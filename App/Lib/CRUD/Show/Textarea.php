<?php

namespace CRUD\Show;

class Textarea extends Unit {

  public function val($val) {
    return parent::val(nl2br('' . $val));
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-textarea">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }
}
