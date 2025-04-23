<?php
require_once __DIR__ . '/../../controller/AuthController.php';

use root_dev\Controller\AuthController;

$_POST['role'] = 'instructor';

$auth = new AuthController();
$auth->register();

include 'layout/side-header.php';

?>

<h1>fuck</h1>

