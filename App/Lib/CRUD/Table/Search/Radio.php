<?php

namespace CRUD\Table\Search;

class Radio extends Items {
  public function __toString() {
    $return = '';
    
    if (!$this->items)
      return $return;

    $return .= '<div class="search-row">';
      $return .= '<b>' . $this->title . '</b>';
      $return .= '<div class="search-radios">';
        $return .= implode('', array_map(function($item) { return '<label><input type="radio" name="' . $this->key . '" value="' . $item['value'] . '"' . ($this->val && $this->val == $item['value'] ? ' checked' : '') . ' /><span>' . $item['text'] . '</span></label>'; }, $this->items));
      $return .= '</div>';
    $return .= '</div>';
    return $return;
  }
}