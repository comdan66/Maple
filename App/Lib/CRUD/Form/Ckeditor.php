<?php

namespace CRUD\Form;

class Ckeditor extends Textarea {
  protected function getContent() {
    $this->type('ckeditor');
    return parent::getContent();
  }
}