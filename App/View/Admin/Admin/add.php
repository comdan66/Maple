<?php

use \CRUD\Form\Image    as Image;
use \CRUD\Form\Input    as Input;
use \CRUD\Form\Checkbox as Checkbox;

echo $form->back();

echo $form->form(function() {
  
  Image::create('avatar', '頭像')
       ->accept('image/*');

  Input::create('name', '名稱')
       ->must()
       ->focus();

  Input::create('account', '帳號')
       ->must();

  Input::create('password', '密碼')
       ->must()
       ->type('password');

  Checkbox::create('roles', '特別權限')
          ->items(\M\AdminRole::ROLE);
});



