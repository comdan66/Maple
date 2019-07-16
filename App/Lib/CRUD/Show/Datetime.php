<?php

namespace CRUD\Show;

class Datetime extends Unit {

  public function val($val) {
    return parent::val('' . $val);
  }

  public function getContent() {
    $val = \DateTime::createFromFormat('Y-m-d H:i:s', $this->val) !== false ? $this->val : '';

    if ($val) {
      $time = strtotime($val);
      $time = \timeago($time);
    } else {
      $time = [];
    }

    $return = '';
    $return .= '<div class="detail-datetime">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . ($val ? '<span>' . $val . '</span>' . ($time ? '<span>' . implode('', $time) . '</span>' : '') : '') . '</div>';
    $return .= '</div>';
    return $return;
  }
}
