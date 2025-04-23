<?php
require_once __DIR__ . '/../../controller/AuthController.php';

use root_dev\Controller\AuthController;

$_POST['role'] = 'users';

$auth = new AuthController();
$auth->register();

include 'layout/side-header.php';

?>

<h1>this for niiga users</h1>