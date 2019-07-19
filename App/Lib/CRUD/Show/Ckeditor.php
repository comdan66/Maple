<?php

namespace CRUD\Show;

class Ckeditor extends Unit {

  public function val($items) {
    return parent::val('' . $items);
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="detail-ckeditor">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }
}