<?php

namespace M;

class Backup extends Model {
  const TYPE_DB        = 'db';
  const TYPE_INFO      = 'info';
  const TYPE_WARNING   = 'warning';
  const TYPE_ERROR     = 'error';
  const TYPE_BENCHMARK = 'benchmark';
  const TYPE_MODEL     = 'model';
  const TYPE_UPLOADER  = 'uploader';
  const TYPE_SAVE_TOOL = 'saveTool';
  const TYPE_THUMBNAIL = 'thumbnail';
  const TYPE_QUERY     = 'query';
  const TYPE_OTHER     = 'other';
  const TYPE = [
    self::TYPE_DB        => '資料庫',
    self::TYPE_INFO      => '訊息',
    self::TYPE_ERROR     => '錯誤',
    self::TYPE_WARNING   => '警告',
    self::TYPE_MODEL     => 'Model',
    self::TYPE_UPLOADER  => '上傳器',
    self::TYPE_SAVE_TOOL => '上傳工具',
    self::TYPE_THUMBNAIL => '縮圖',
    self::TYPE_BENCHMARK => '效能紀錄',
    self::TYPE_QUERY     => 'Query',
    self::TYPE_OTHER     => '其他',
  ];

  const STATUS_FAILURE = 'failure';
  const STATUS_SUCCESS = 'success';
  const STATUS = [
    self::STATUS_FAILURE => '失敗', 
    self::STATUS_SUCCESS => '成功',
  ];

  const IS_READ_YES = 'yes';
  const IS_READ_NO  = 'no';
  const IS_READ = [
    self::IS_READ_YES => '已讀', 
    self::IS_READ_NO  => '未讀',
  ];
}

Backup::fileUploader('file')
      ->default();