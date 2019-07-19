<?php

namespace CRUD\Form;

class Images extends Unit {
  private $val = [], $accept = null;

  public function accept(string $accept) {
    $this->accept = $accept;
    return $this;
  }

  public function val(array $val) {
    $this->val = $val;
    return $this;
  }

  protected function getContent() {
    $val = array_map(function($val) {
      $id = null;

      if ($val instanceof \_M\ImageUploader && isset($val->obj()->id)) {
        $id = $val->obj()->id;
        $val = $val->url();
      }

      $return = '';
      $return .= '<div class="drop-img' . ($val !== '' ? ' has' : '') . '">';
        $return .= '<input type="hidden" name="_' . $this->name .'[]" value="' . $id . '">';
        $return .= '<img src="' . $val . '" />';
        $return .= '<input' . attr([
          'type' => 'file',
          'name' => $this->name . '[]',
          'accept' => $this->accept
        ]) .'/>';
        $return .= '<label></label>';
      $return .= '</div>';
      return $return;
    }, array_merge($this->val, ['']));

    $class = implode(' ', array_filter(['form-images', $this->must ? 'must' : null, $this->class], function($t) { return $t !== null && $t !== ''; }));

    $return = '';
    $return .= '<div' . attr(['class' => $class, 'id' => $this->id]) . '>';
      $return .= '<b>' . $this->title . '</b>';
      $return .= $this->tip !== '' ? '<span>' . $this->tip . '</span>' : '';
      $return .= '<div>';
        $return .= $val ? '<div class="multi-drop-imgs">' . implode('', $val) . '</div>' : '';
      $return .= '</div>';
    $return .= '</div>';
        
    return $return;
  }
}