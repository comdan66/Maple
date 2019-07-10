<?php

namespace M;

class AdminRole extends Model {
  const ROLE_ROOT    = 'root';
  const ROLE_ADMIN   = 'admin';

  const ROLE = [
    self::ROLE_ROOT => '最高權限', 
    self::ROLE_ADMIN => '後台管理者',
  ];
}
