<?php

namespace CRUD\Show;

class Links extends Unit {

  public function val($val) {
    parent::val(implode('', array_map(function($t) {
      if (is_array($t)) return '<a href="' . array_shift($t) . '">' . array_shift($t) . '</a>';
      if ($t instanceof \HTML\A) return $t;
      return '<a href="' . $t . '">' . $t . '</a>';
    }, is_array($val) ? $val : [])));
    return $this;
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-links">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }
}