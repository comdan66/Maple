<?php

class Crontab extends CliController {
  protected $crontab = null;
  
  public function __construct() {
    parent::__construct();

    ifError(function($error) {
      // 發推播 or Mail
      echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
      var_dump($error);
      exit();
    });

    $this->crontab = \M\Crontab::create([
      'title' => '',
      'method' => Router::methodName(),
      'params' => json_encode(Router::params()),
      'status' => \M\Crontab::STATUS_FAILURE,
      'isRead' => \M\Crontab::IS_READ_NO,
      'sTime' => microtime(true),
      'eTime' => 0,
      'rTime' => 0,
    ]);

    $this->crontab || error('新增 Crontab 失敗！');
  }

  public function __destruct() {
    if (!$this->crontab) return;
    $this->crontab->status = \M\Crontab::STATUS_SUCCESS;
    $this->crontab->isRead = \M\Crontab::IS_READ_YES;
    $this->crontab->eTime  = microtime(true);
    $this->crontab->rTime  = $this->crontab->eTime - $this->crontab->sTime;
    $this->crontab->save();
  }

  public function backupDb() {
    $this->crontab->title = '每天晚上 2 點執行，備份資料庫';

    $backup = \M\Backup::create(['file' => '', 'size' => 0, 'type' => \M\Backup::TYPE_DB, 'status' => \M\Backup::STATUS_FAILURE, 'isRead' => \M\Backup::IS_READ_NO, 'memo' => '']);

    $backup || gg('資料庫建立失敗！');

    Load::systemFunc('File');
    Load::systemFunc('Dir');

    $models = array_filter(arrayFlatten(dirMap(PATH_APP_MODEL)), function($t) { return pathinfo($t, PATHINFO_EXTENSION) === 'php'; });
    $models = array_filter(array_map(function($m) { return pathinfo($m, PATHINFO_FILENAME); }, $models), function($m) { return class_exists("\\M\\" . $m); });

    $models = array_combine($models, array_map(function($m) { $m = "\\M\\" . $m; return array_map('\M\toArray', $m::all()); }, $models));
    fileWrite($path = PATH_FILE_TMP . 'backup_' . \M\Backup::TYPE_DB . '_' . date ('YmdHis') . '.json', json_encode($models)) || gg('寫入檔案失敗！');

    $backup->size = filesize($path);
    $backup->status = \M\Backup::STATUS_SUCCESS;
    $backup->isRead = \M\Backup::IS_READ_YES;

    if (!$backup->save()) {
      $backup->memo = '儲存失敗！';
      return $backup->save();
    }

    if (!$backup->file->put($path)) {
      $backup->memo = '上傳檔案失敗！';
      return $backup->save();
    }
  }

  public function backupLogs() {
    $this->crontab->title = '每天晚上 3 點執行，備份 Log';

    $beforeDay = Router::param('beforeDay');
    $beforeDay !== null || $beforeDay = 1;
    $beforeDay = date('Y-m-d', strtotime(date('Y-m-d') . '-' . $beforeDay . 'day'));

    Load::systemFunc('File.php');

    foreach (['info', 'warning', 'error', 'benchmark', 'model', 'uploader', 'saveTool', 'thumbnail', 'query'] as $method) {
      if (!array_key_exists($method, \M\Backup::TYPE))
        continue;

      if (!$backup = \M\Backup::create(['file' => '', 'size' => 0, 'type' => $method, 'status' => \M\Backup::STATUS_FAILURE, 'isRead' => \M\Backup::IS_READ_NO, 'memo' => '']))
        continue;

      if (!file_exists($path = PATH_FILE_LOG . ucfirst($method) . DIRECTORY_SEPARATOR . $beforeDay . '.log')) {
        $backup->status = \M\Backup::STATUS_SUCCESS;
        $backup->isRead = \M\Backup::IS_READ_YES;
        $backup->memo = '.log 檔案不存在！';
        $backup->save();
        continue;
      }

      if (!is_readable($path)) {
        $backup->memo = '.log 檔案不可讀！';
        $backup->save();
        continue;
      }
 
      if (!fileWrite($path2 = PATH_FILE_TMP . 'backup_' . $method . '_' . date ('YmdHis') . '.log', Xterm::decode(fileRead($path)))) {
        $backup->memo = '轉存 .log 檔案失敗！';
        $backup->save();
        continue;
      }

      $backup->size = filesize($path2);
      $backup->status = \M\Backup::STATUS_SUCCESS;
      $backup->isRead = \M\Backup::IS_READ_YES;
      if (!$backup->save()) {
        $backup->memo = '儲存失敗！';
        $backup->save();
        continue;
      }

      if (!$backup->file->put($path2)) {
        $backup->memo = '上傳檔案失敗！';
        $backup->save();
        continue;
      }
      
      @unlink($path);
    }
  }
}
