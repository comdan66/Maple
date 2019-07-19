<?php

return [
  [
    'text' => '後台設定',
    'icon' => 'icon-AdminMenu-001',
    'items' => [
      ['icon' => 'icon-AdminMenu-002', 'router' => 'AdminMainIndex',           'text' => '後台首頁'],
      ['icon' => 'icon-AdminMenu-003', 'router' => 'AdminAdminIndex',          'text' => '管理員帳號'],
      ['icon' => 'icon-AdminMenu-004', 'router' => 'AdminBackupIndex',         'text' => '每日備份紀錄', 'datas' => ['label' => 'backup-isRead', 'cnt' => \M\Backup::count('isRead = ?', \M\Backup::IS_READ_NO)]],
      ['icon' => 'icon-AdminMenu-005', 'router' => 'AdminCrontabIndex',        'text' => '排程執行紀錄', 'datas' => ['label' => 'crontab-isRead', 'cnt' => \M\Crontab::count('isRead = ?', \M\Crontab::IS_READ_NO)]],
      ['icon' => 'icon-AdminMenu-006', 'router' => 'AdminAdminAjaxErrorIndex', 'text' => '後台錯誤紀錄', 'datas' => ['label' => 'adminAjax-isRead', 'cnt' => \M\AdminAjaxError::count('isRead = ?', \M\AdminAjaxError::IS_READ_NO)]]
    ]
  ],
];
