--TEST--
Services_urlTea::lookup()
--FILE--
<?php

require_once 'tests-config.php';
require_once 'Services/urlTea.php';

try {
    $urlTea = new Services_urlTea();
    $src = $urlTea->lookup('http://urltea.com/1p1g');
    echo $src . "\n";
} catch (Services_urlTea_Exception $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECTREGEX--
http:\/\/www.joestump.net\/?
