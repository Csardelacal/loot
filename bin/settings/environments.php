<?php

use spitfire\core\Environment;

/*
 * Creates a test environment that can be used to store configuration that affects
 * the behavior of an application.
 */
$e = new Environment('test');

$e->set('db', 'mysqlpdo://root:root@localhost/loot?prefix=loot_&encoding=utf8');
$e->set('SSO', 'http://1502978797:pXBMLEpBGtxUUzSwCORquRHtpFKdSTbE0qyDUaCnEr5crQ8@localhost/Auth');