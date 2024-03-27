<?php
$requires[] = 'deco-admin-branding/init.php';
// $requires[] = 'deco-soc-sharing/init.php';
$requires[] = 'deco-widgets/init.php';
$requires[] = 'deco-acf/init.php';
// $requires[] = 'deco-sphinx-search/init.php';

$requires[] = 'deco-cool-search_/init.php';

//$requires[] = 'cool-search/init.php';
// $requires[] = 'deco-login/init.php';
// $requires[] = 'desocial-accounts/init.php';
 $requires[] = 'deco-ajax/init.php';
//$requires[] = 'deco-load-more/init.php';
foreach ( $requires as $require_one ) {
	require_once DECO_FRAMEWORK_MODULES_DIR . $require_one;
}
