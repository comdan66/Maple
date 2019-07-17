<?php

namespace CRUD\Show;

abstract class Unit {
  protected $title = '', $val, $isMin = true, $show;

  public function __construct(string $title) {
    $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    
    foreach ($traces as $trace)
      if (isset($trace['object']) && $trace['object'] instanceof \CRUD\Show && method_exists($trace['object'], 'appendUnit') && ($this->show = $trace['object']->appendUnit($this)))
        break;

    $this->title($title);
  }

  public static function create(string $title = '') {
    return new static($title);
  }
  
  public function title(string $title) {
    $this->title = $title;
    return $this;
  }
  
  public function val($val) {
    $this->val = $val;
    return $this;
  }

  protected function getContent() {
    $return = '';
    $return .= '<div class="detail-unit">';
      $return .= $this->title !== '' ? '<b>' . $this->title . '</b>' : '';
      $return .= '<div>' . $this->val . '</div>';
    $return .= '</div>';
    return $return;
  }

  public function __toString() {
    return $this->getContent();
  }
}