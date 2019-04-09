<?php

require_once '../vendor/autoload.php';

use Alonexy\Xmysql;

$aa = new Xmysql('127.0.0.1','test','123456','test');
$aa->execute('select * from `order_2018_4` where symbol = "XAU%" order by id desc limit 10;',1,1,1);