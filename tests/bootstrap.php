<?php
namespace NonceTest;

use WpTestsStarter\WpTestsStarter;

$base_dir = dirname(__DIR__);

require_once $base_dir . '/vendor/autoload.php';

//Include wordpress from wordpress-dev package
$starter = new WpTestsStarter("{$base_dir}/vendor/inpsyde/wordpress-dev");

// phpunit defined these constants for you
$starter->defineDbName(DB_NAME);
$starter->defineDbUser(DB_USER);
$starter->defineDbPassword(DB_PASSWORD);
$starter->setTablePrefix(DB_TABLE_PREFIX);

// this will finally create the wp-tests-config.php and include the wordpress core tests bootstrap
$starter->bootstrap();
