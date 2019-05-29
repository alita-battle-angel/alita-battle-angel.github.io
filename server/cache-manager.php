<?php

function get_last_modified_time($file_name)
{
  $file_path = __DIR__ . "/$file_name";
  if (!file_exists($file_path)) {
    $handle = fopen($file_path, 'w') or die('Cannot open file:  ' . $file_path);
    fclose($handle);
  }

  $handle = fopen($file_name, 'r') or die('Cannot open file:  ' . $file_name);
  $last_modified_time = filemtime($file_path) or 0;
  fclose($handle);

  return $last_modified_time;
}

function is_cache_fresh($file_name, $life_span = 60)
{
  $file_path = __DIR__ . "/$file_name";

  $last_modified_time = get_last_modified_time($file_name);
  $current_time = time();

  if ($current_time - $last_modified_time > $life_span || filesize($file_path) === 0) {
    return false;
  }

  return true;
}

function read_from_cache($file_name)
{
  $file_path = __DIR__ . "/$file_name";

  $size = filesize($file_path);
  if ($size > 0) {
    $handle = fopen($file_path, 'r') or die('Cannot open file:  ' . $file_path);
    $file_content = fread($handle, $size);
    fclose($handle);

    return $file_content;
  }

  return null;
}

function update_cache($file_name, $content)
{
  $file_path = __DIR__ . "/$file_name";

  $handle = fopen($file_path, 'w') or die('Cannot open file:  ' . $file_path);
  fwrite($handle, $content);
  fclose($handle);
  clearstatcache();

  return $content;
}
