<?php

$path = ini_get('include_path');
ini_set('include_path', realpath(dirname(__FILE__) . '/..') . ':' . $path);

?>
