--TEST--
Services_urlTea::create() == Services_urlTea::lookup()
--FILE--
<?php

require_once 'tests-config.php';
require_once 'Services/urlTea.php';

try {
    $t = new Services_urlTea();
    $url = 'http://pear.php.net/';
    $tea = $t->create($url);
    $dest = $t->lookup($tea);
    var_dump(($url == $dest));
} catch (Services_urlTea_Exception $e) {
    echo $e->getMessage();
}

?>
--EXPECT--
bool(true)
