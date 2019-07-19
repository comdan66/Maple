<?php

namespace CRUD\Show;

class Json extends Unit {
  protected $isJson = true;

  public function val($val) {
    $json = json_decode($val, true);
    $this->isJson = json_last_error() === JSON_ERROR_NONE;

    parent::val(
      $this->isJson
      ? '<pre data-title="' . '共有：' . number_format(mb_strlen($val)) . ' 個字元' . '">' . dump($json) . '</pre>'
      : $val
    );

    return $this;
  }

  public function getContent() {
    $return = '';
    $return .= '<div class="' . ($this->isJson ? 'detail-json' : 'detail-text') . '">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }
}
