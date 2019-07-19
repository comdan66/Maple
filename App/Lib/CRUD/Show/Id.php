<?php

namespace CRUD\Show;

class Id extends Unit {

  public static function create(string $title = null) {
    return new static('ID');
  }

  public function val($val) {
    return parent::val('' . ($val instanceof \M\Model ? $val->id ?? '' : $val));
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-id">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . ($this->val ?? isset($this->show) ? $this->show->obj()->id : '') . '</div>';
    $return .= '</div>';
    return $return;
  }
}
