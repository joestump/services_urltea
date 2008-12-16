--TEST--
Services_urlTea::create()
--FILE--
<?php

require_once 'tests-config.php';
require_once 'Services/urlTea.php';

try {
    $urlTea = new Services_urlTea();
    $url = $urlTea->create('http://www.joestump.net');
    echo $url . "\n";
} catch (Services_urlTea_Exception $e) {
    echo $e->getMessage();
}

?>
--EXPECTREGEX--
http:\/\/urltea.com\/[a-zA-Z0-9]+
