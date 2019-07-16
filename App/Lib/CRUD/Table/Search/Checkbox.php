<?php

namespace CRUD\Table\Search;

class Checkbox extends Items {
  public function __toString() {
    $return = '';
    
    if (!$this->items)
      return $return;

    $return .= '<div class="search-row">';
      $return .= '<b>' . $this->title . '</b>';
      $return .= '<div class="search-checkboxs">';
        $return .= implode('', array_map(function($item) { return '<label><input type="checkbox" name="' . $this->key . '[]" value="' . $item['value'] . '"' . ($this->val && (is_array($this->val) ? in_array($item['value'], $this->val) : $this->val == $item['value']) ? ' checked' : '') . ' /><span>' . $item['text'] . '</span></label>'; }, $this->items));
      $return .= '</div>';
    $return .= '</div>';

    return $return;
  }
}