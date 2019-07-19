<?php

namespace CRUD\Form;

class Image extends Unit {
  private $val = '', $accept = null;
  
  public function __construct($title, $name) {
    parent::__construct($title, $name);
    $this->obj->hasImage();
  }

  public function val($val = '') {
    $this->val = $val;
    return $this;
  }

  public function accept(string $accept) {
    $this->accept = $accept;
    return $this;
  }

  protected function getContent() {
    $this->val instanceof \_M\ImageUploader && $this->val = $this->val->url();
    
    $class = implode(' ', array_filter(['form-image', $this->must ? 'must' : null, $this->class], function($t) { return $t !== null && $t !== ''; }));

    $return = '';
    $return .= '<div' . attr(['class' => $class, 'id' => $this->id]) . '>';
      $return .= '<b>' . $this->title . '</b>';
      $return .= $this->tip !== '' ? '<span>' . $this->tip . '</span>' : '';
      $return .= '<div>';
        $return .= '<label class="drop-img' . ($this->val !== '' ? ' has' : '') . '">';
          $return .= '<img src="' . $this->val . '" />';
          $return .= '<input' . attr([
              'type' => 'file',
              'name' => $this->name,
              'accept' => $this->accept,
              'required' => !$this->val ? $this->must ? true : null : null
            ]) . '/>';
        $return .= '</label>';
      $return .= '</div>';
    $return .= '</div>';
           
    return $return;
  }
}