<?php

namespace CRUD\Form;

class Multi extends Unit {
  private $rowClosure = null;
  private $units = [];
  private $val = [];

  public function val(array $val) {
    $this->val = $val;
    return $this;
  }

  public function row($rowClosure) {
    $this->rowClosure = $rowClosure;
    return $this;
  }

  public function appendUnit(\CRUD\Form\Unit\Multi $unit) {
    array_push($this->units, $unit);
    return $this;
  }

  private function setCalcWidth() {
    $widths = array_sum(array_filter(array_map(function($unit) { return $unit->getWidth(); }, $this->units), function($unit) { return $unit !== null; }));
    $nulls = array_filter($this->units, function($unit) { return $unit->getWidth() === null; });
    
    if (!$nulls) {
      $widths = 0;
      $unit = $this->units[count($this->units) - 1];
      $unit->widthString(null);
      $nulls = [$unit];
    }

    $count = count($nulls);
    $calc = $count > 1 ? 'calc((100%' . ($widths ? ' - ' . $widths . 'px' : '') . ') / ' . $count . ')' : ('calc(100%' . ($widths ? ' - ' . $widths . 'px' : '') . ')');
    foreach ($this->units as $unit)
      if ($unit->getWidth() === null) $unit->widthString($calc);
      else $unit->widthString($unit->getWidth() . 'px');
    return $this;
  }

  protected function getContent() {
    if (!($this->rowClosure && is_callable($rowClosure = $this->rowClosure)))
      return '';

    $val = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) ? \CRUD\Form::$flash[$this->name] : $this->val;

    $rowClosure();
    $this->setCalcWidth();

    $class = implode(' ', ['form-multi', $this->must ? 'must' : null]);
    $return = '';
    $return .= '<div' . attr(['class' => $class]) . '>';
      $return .= '<b>' . $this->title . '</b>';
      $return .= $this->tip !== '' ? '<span>' . $this->tip . '</span>' : '';
      $return .= '<div class="form-multi-rows" data-name="' . $this->name . '" data-formats=\'' . json_encode(array_map(function($unit) { return $unit->getFormat(); }, $this->units)) . '\' data-rows=\'' . json_encode($val) . '\'></div>';
    $return .= '</div>';

    return $return;
  }
}