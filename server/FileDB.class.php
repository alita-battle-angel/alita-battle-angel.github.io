<?php

class FileDB {
  private $file_name;
  private $file_handle;
  private $table = [];

  public static function get_page_of ($list, $page_no = 1, $item_per_page = 50) {
    $page_no--;
    if ($page_no < 0) {
      $page_no = 0;
    }

    $start = $page_no * $item_per_page;
    $page = array_slice($list, $start, $item_per_page);

    return [
      'page' => $page_no + 1,
      'item_per_page' => $item_per_page,
      'total_pages' => sizeof($list) === 0 ? 1 : ceil(sizeof($list) / $item_per_page),
      'data' => $page
    ];
  }

  public function __construct ($file_name) {
    $this->file_name = $file_name;
    $this->init_db_file();
  }

  private function init_db_file () {
    $file_path = __DIR__ . "/{$this->file_name}";
    if (!file_exists($file_path)) {
      $this->file_handle = fopen($file_path, 'w+') or die('Cannot open file:  ' . $file_path);
    } else {
      $this->file_handle = fopen($this->file_name, 'r') or die('Cannot open file:  ' . $this->file_name);
    }
    $size = filesize($file_path);

    if ($size !== 0) {
      $this->table = json_decode(fread($this->file_handle, $size), true) or [];
    }

    fclose($this->file_handle);
  }

  private function update_file () {
    $this->file_handle = fopen($this->file_name, 'w') or die('Cannot open file:  ' . $this->file_name);
    fwrite($this->file_handle, json_encode($this->table));
    fclose($this->file_handle);
    clearstatcache();
  }

  public function destroy () {

  }

  public function pagination () {

  }

  public function get_all () {
    return $this->table;
  }

  public function get_all_by_id ($ids = [], $id_column = 'id') {
    $id_lead_list = [];

    foreach ($this->table as $item) {
      $id_lead_list[$item[$id_column]] = $item;
    };

    return array_values(array_intersect_key($id_lead_list, array_flip($ids)));
  }

  public function get_all_older_than ($life_span = 86400) {
    $current_time = time();
    $old_records = [];
    foreach ($this->table as $id => $record) {
      if ($current_time - $record['_SAVED_AT'] > $life_span) {
        $old_records[] = $record;
      }
    }

    return $old_records;
  }

  public function find_by_id ($id, $id_column = 'id') {
    $key = array_search($id, array_column($this->table, $id_column));
    if (is_numeric($key)) return $this->table[$key];

    return null;
  }

  public function find_absent_by_id ($ids = [], $id_column = 'id') {
    $absents = [];
    foreach ($ids as $id) {
      if (!in_array($id, array_column($this->table, $id_column))) {
        $absents[] = $id;
      }
    }

    return $absents;
  }

  public function get_last_index () {
    return sizeof($this->table);
  }

  public function add ($record) {
    $current_time = time();
    $record['_SAVED_AT'] = $current_time;
    $this->table[] = $record;

    $this->update_file();

    return $record;
  }

  public function update ($record, $id_column = 'id') {
    $ids = array_column($this->table, $id_column);
    $key = array_search($record[$id_column], $ids);

    if ($key) {
      $current_time = time();
      $record['_SAVED_AT'] = $current_time;
      $this->table[$key] = $record;
    }

    $this->update_file();

    return $this->table[$key];
  }

  public function batch_update ($records = [], $id_column = 'id') {
    $ids = array_column($this->table, $id_column);
    $current_time = time();
    foreach ($records as $record) {
      $record['_SAVED_AT'] = $current_time;
      $key = array_search($record[$id_column], $ids);

      if ($key) {
        $this->table[$key] = $record;
      } else {
        $this->table[] = $record;
      }
    }

    $this->update_file();
  }
}
