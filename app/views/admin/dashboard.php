<?php
require_once __DIR__ . '/../../../config/database.php';

$db = Database::connect();

// Count instructors
$stmtInstructor = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'instructor'");
$stmtInstructor->execute();
$instructorCount = $stmtInstructor->fetchColumn();

// Count learners
$stmtLearner = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'user'");
$stmtLearner->execute();
$learnerCount = $stmtLearner->fetchColumn();

include "layout/side-header.php";
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
    <!-- Instructor Count -->
    <div class="bg-white shadow rounded-lg p-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Total Instructors</h2>
            <p class="text-3xl font-bold text-blue-600"><?= $instructorCount; ?></p>
        </div>
        <div class="text-blue-600 text-4xl">
            📘
        </div>
    </div>

    <!-- Learner Count -->
    <div class="bg-white shadow rounded-lg p-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Total Learners</h2>
            <p class="text-3xl font-bold text-green-600"><?= $learnerCount; ?></p>
        </div>
        <div class="text-green-600 text-4xl">
            🎓
        </div>
    </div>
</div>

