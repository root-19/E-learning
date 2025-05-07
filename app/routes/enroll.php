<!-- <? 
// require_onc_DIR__ . '/../controller/EnrollmentController.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $course_id = $_POST['course_id'] ?? 0;
//     $user_id = $_SESSION['user_id'];

//     if (!$course_id || !$user_id) {
//         header('Location: /my_learning?error=invalid_request');
//         exit();
//     }

//     $controller = new EnrollmentController();
//     $result = $controller->enrollUser($user_id, $course_id);

//     if ($result['success']) {
//         header('Location: /course-view/' . $course_id);
//     } else {
//         header('Location: /my_learning?error=' . urlencode($result['message']));
//     }
//     exit();
// } else {
//     header('Location: /my_learning');
//     exit();
// } 