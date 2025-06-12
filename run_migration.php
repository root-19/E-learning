<?php
require_once 'config/database.php';
require_once 'migrations/2025_04_28_000001_add_bio_and_profile_image_to_users.php';

$pdo = Database::connect();
$migration = new AddBioAndProfileImageToUsers();
$migration->up($pdo); 