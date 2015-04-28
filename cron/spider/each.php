<?php
// // php /www/find_tel/cron/spider/each.php
// define( 'DOCUMENT_ROOT', '/www/find_tel/' );
// $hand_dir	= DOCUMENT_ROOT . 'spider/hands/';
// foreach(scandir( $hand_dir ) AS $hand )
// {
// 	if( $hand == '.' || $hand == '..' ) continue;

// 	for( $i = 1; $i < 5000; $i++ )
// 	{
// 		echo `php /www/find_tel/cron/spider/doing.php {$hand}`;
// 		sleep(1);
// 	}
// }

// php /www/find_tel/cron/spider/each.php
$index	= 1;
while(TRUE)
{
	echo "index:",$index++,"\n";
	echo `php /www/find_tel/cron/spider/doing.php dianping.com.php`;
	sleep(10);
}
