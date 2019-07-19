<?php

Router::dir('admin', 'Admin', function() {

  // Admin
  Router::get('admins')->controller('Admin@index');
  Router::get('admins/add')->controller('Admin@add');
  Router::post('admins')->controller('Admin@create');
  Router::get('admins/(id:id)/edit')->controller('Admin@edit');
  Router::put('admins/(id:id)')->controller('Admin@update');
  Router::get('admins/(id:id)')->controller('Admin@show');
  Router::delete('admins/(id:id)')->controller('Admin@delete');
  Router::post('admins/(id:id)/column/name')->controller('Admin@columnName');
  
  // AdminLog
  Router::get('admin/(adminId:id)/logs/(id:id)')->controller('AdminLog@show');

  // AdminAjaxError
  Router::get('ajaxErrors')->controller('AdminAjaxError@index');
  Router::post('ajaxErrors')->controller('Main@ajaxErrorCreate');
  Router::get('ajaxErrors/(id:id)')->controller('AdminAjaxError@show');
  Router::post('ajaxErrors/(id:id)/read')->controller('AdminAjaxError@read');
});