<?php

namespace CRUD\Show;

class Texts extends Unit {

  public function val($val) {
    parent::val(implode('', array_map(function($t) {
      return '<span>' . $t . '</span>';
    }, is_array($val) ? $val : [$val])));
    return $this;
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-texts">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }
}