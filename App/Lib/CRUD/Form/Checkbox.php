<?php

namespace CRUD\Form;

class Checkbox extends \CRUD\Form\Unit\Items {
  private $val = [];

  public function val(array $val) {
    $this->val = $val;
    return $this;
  }

  public static function inArray($var, $arr) {
    foreach ($arr as $val)
      if (($var === 0 ? '0' : $var) == $val)
        return true;
    return false;
  }

  protected function getContent() {
    $value = \CRUD\Form::$flash !== null ? isset(\CRUD\Form::$flash[$this->name]) ? \CRUD\Form::$flash[$this->name] : [] : $this->val;
    is_array($value) || $value = [];

    $value = implode('', array_map(function($item) use ($value) {
      $return = '';
      $return .= '<label>';
        $return .= '<input type="checkbox" value="' . $item['value'] . '" name="' . $this->name . '[]"' . (Checkbox::inArray($item['value'], $value) ? ' checked' : '') . '/>';
        $return .= '<span>' . $item['text'] . '</span>';
      $return .= '</label>';
      return $return;
    }, $this->items));

    $class = implode(' ', array_filter(['form-checkboxs', $this->must ? 'must' : null, $this->class], function($t) { return $t !== null && $t !== ''; }));
    $return = '';
    $return .= '<div' . attr(['class' => $class, 'id' => $this->id]) . '>';
      $return .= '<b>' . $this->title . '</b>';
      $return .= $this->tip !== '' ? '<span>' . $this->tip . '</span>' : '';
      $return .= '<div>';
        $return .= $value;
      $return .= '</div>';
    $return .= '</div>';

    return $return;
  }
}