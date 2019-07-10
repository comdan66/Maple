<?php

return [
  'up' => [
    "INSERT INTO `Admin` (`id`, `name`, `account`, `password`)VALUES(1, '最高管理員', 'root', '" . password_hash('m@pLe12Eq', PASSWORD_DEFAULT) . "');",
    "INSERT INTO `AdminRole` (`id`, `adminId`, `role`)VALUES(1, 1, 'root');",
  ],

  'down' => [
    "TRUNCATE TABLE `AdminRole`;",
    "TRUNCATE TABLE `Admin`;",
  ],

  'at' => "2019-06-24 23:07:39"
];

# 欄位格式
  # 主鍵
    // `id`        int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',

  # 外鍵
    // `userId`    int(11) unsigned NOT NULL COMMENT 'User ID',
    // `userId`    int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID',

  # 字串
    // `cover`     varchar(50)  COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面',
    // `title`     varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '標題',
    // `content`   text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '內容',

  # 列舉
    // `enable`    enum('yes', 'no') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '啟用',
    // `enable`    enum('yes', 'no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no' COMMENT '啟用',

  # 小數
    // `price`     decimal(11,2) NOT NULL DEFAULT '0.00',

# 資料表
  # 新增
    // CREATE TABLE `{資料表名稱}` (
    //   `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    //   [{欄位格式}]
    //   `updateAt`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間',
    //   `createAt`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增時間',
    //   PRIMARY KEY (`id`),
    //   KEY `userId_index` (`userId`)
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='資料表註解';
  
  
  # 刪除
    // DROP TABLE IF EXISTS `{資料表名稱}`;

  # 清空
    // TRUNCATE TABLE `{資料表名稱}`;

# 欄位
  # 新增
    // ALTER TABLE `{資料表名稱}` ADD {新增的欄位格式} AFTER `{哪個欄位之後}`;",

  # 刪除
    // ALTER TABLE `{資料表名稱}` DROP COLUMN `{欄位名稱}`;

  # 變更
    // ALTER TABLE `{資料表名稱}` CHANGE `{原欄位名稱}` {新欄位格式}
