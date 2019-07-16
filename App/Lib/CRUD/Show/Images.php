<?php

namespace CRUD\Show;

class Images extends Unit {

  public function val($val) {
    parent::val(implode('', array_filter(array_map(function($val) {
      $val instanceof \_M\ImageUploader && $val = $val->url();
      return isset($val) && $val !== '' ? '<figure data-bgurl="' . $val . '" data-ori="' . $val . '"></figure>' : '';
    }, is_array($val) ? $val : [$val]))));
    return $this;
  }

  protected function getContent() {
    $return = '';
    $return .= '<div class="detail-medias">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }
}