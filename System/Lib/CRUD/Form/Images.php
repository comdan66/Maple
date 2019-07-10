<?php

namespace CRUD\Form;

class Images extends Unit {
  private $val = [], $accept;

  public function accept($accept) {
    $this->accept = $accept;
    return $this;
  }

  public function val(array $val) {
    $this->val = $val;
    return $this;
  }

  protected function getContent() {

    $attrs = [
      'type' => 'file',
      'name' => $this->name . '[]',
    ];

    $this->accept && $attrs['accept'] = $this->accept;

    $return = '';
    $return .= '<div class="multi-drop-imgs">';

    $return .= implode('', array_map(function($val) use ($attrs) {
      $id = null;

      if ($val instanceof \_M\ImageUploader && isset($val->obj()->id)) {
        $id = $val->obj()->id;
        $val = $val->url();
      }

      $return = '';
      $return .= '<div class="drop-img">';
        $return .= '<input type="hidden" name="_' . $this->name .'[]" value="' . $id . '">';
        $return .= '<img src="' . $val . '" />';
        $return .= '<input' . attr($attrs) .'/>';
        $return .= '<a></a>';
      $return .= '</div>';
      return $return;
    }, array_merge($this->val, [''])));

    $return .= '</div>';

    return $return;
  }
}