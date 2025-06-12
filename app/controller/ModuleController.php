<?php
namespace app\controller;

error_reporting(E_ALL); // Enable all error reporting
ini_set('display_errors', 1); // Display errors directly

require_once __DIR__ . '/../models/Module.php';
require_once __DIR__ . '/../../config/database.php';

use app\models\Module;
use PDO;
use PDOException;

class ModuleController {
    private $conn;

    public function __construct() {
        $this->conn = \Database::connect();
        var_dump($this->conn); // Debugging: check database connection
    }

    public function create($course_title, $course_image, $description) {
        $module = new Module();
        return $module->createCourse($course_title, $course_image, $description);
    }

    public function listCourses() {
        $module = new Module();
        return $module->getAllCourses();
    }

    public function getCourseById($courseId) {
        $module = new Module();
        return $module->getCourseById($courseId);
    }

    public function getChaptersForCourse($courseId) {
        $module = new Module();
        return $module->getChaptersByCourseId($courseId);
    }

    public function getCourseStatus($id) {
        $module = new Module();
        return $module->getCourseStatus($id);
    }

    public function updateCourseStatus($id, $status) {
        $module = new Module();
        return $module->updateCourseStatus($id, $status);
    }

    public function getChapterCount($courseId) {
        $module = new Module();
        return $module->getChapterCount($courseId);
    }

    public function updateChapter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // error_reporting(0); // Temporarily disable error reporting
            // ob_start(); // Start output buffering

            try {
                $chapter_id = $_POST['chapter_id'];
                $course_id = $_POST['course_id'];
                $chapter_title = $_POST['chapter_title'];
                $chapter_content = $_POST['chapter_content'];
                $type = $_POST['type'];

                // Start transaction
                $this->conn->beginTransaction(); 

                // Update chapter details
                $stmt = $this->conn->prepare("
                    UPDATE chapters 
                    SET chapter_title = ?, 
                        chapter_content = ?, 
                        type = ?,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = ? AND course_id = ?
                ");
                
                $stmt->execute([
                    $chapter_title,
                    $chapter_content,
                    $type,
                    $chapter_id,
                    $course_id
                ]);

                // Handle chapter image upload if provided
                if (isset($_FILES['chapter_image']) && $_FILES['chapter_image']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/courses/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $file_extension = strtolower(pathinfo($_FILES['chapter_image']['name'], PATHINFO_EXTENSION));
                    $new_filename = 'chapter_' . $chapter_id . '_' . time() . '.' . $file_extension;
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($_FILES['chapter_image']['tmp_name'], $upload_path)) {
                        // Update chapter image in database
                        $stmt = $this->conn->prepare("UPDATE chapters SET chapter_image = ? WHERE id = ?"); 
                        $stmt->execute([$upload_path, $chapter_id]);
                    }
                }

                $this->conn->commit(); 
                
                ob_clean(); // Clean any buffer output
                // Return JSON response for AJAX
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Chapter updated successfully'
                ]);
                exit();

            } catch (Exception $e) {
                ob_clean(); // Clean any buffer output
                $this->conn->rollBack(); 
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Error updating chapter: ' . $e->getMessage()
                ]);
                exit();
            }
        }
    }

    public function updateChapterMedia() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // error_reporting(0); // Temporarily disable error reporting
            // ob_start(); // Start output buffering

            try {
                $chapter_id = $_POST['chapter_id'];
                $course_id = $_POST['course_id'];
                $media_caption = $_POST['media_caption'] ?? null;
                $old_media_path = null;

                // Get current media path from database to delete old file later
                $stmt = $this->conn->prepare("SELECT media_path FROM chapters WHERE id = ?");
                $stmt->execute([$chapter_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $old_media_path = $result['media_path'];
                }

                $new_media_path = $old_media_path; // Default to old path if no new file uploaded
                $new_media_type = null; // Will be determined by new file or remain null if no new file

                // Handle new media file upload if provided
                if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/chapters/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $file_extension = strtolower(pathinfo($_FILES['media_file']['name'], PATHINFO_EXTENSION));
                    $new_filename = 'chapter_media_' . $chapter_id . '_' . time() . '.' . $file_extension;
                    $uploaded_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($_FILES['media_file']['tmp_name'], $uploaded_path)) {
                        $new_media_path = $uploaded_path;

                        // Determine media type based on extension
                        $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        $video_extensions = ['mp4', 'webm', 'ogg'];
                        $pdf_extensions = ['pdf'];
                        $ppt_extensions = ['ppt'];
                        $pptx_extensions = ['pptx'];
                        $doc_extensions = ['doc'];
                        $docx_extensions = ['docx'];
                        $xls_extensions = ['xls'];
                        $xlsx_extensions = ['xlsx'];
                        $txt_extensions = ['txt'];
                        $zip_extensions = ['zip', 'rar'];

                        if (in_array($file_extension, $image_extensions)) {
                            $new_media_type = 'image';
                        } elseif (in_array($file_extension, $video_extensions)) {
                            $new_media_type = 'video';
                        } elseif (in_array($file_extension, $pdf_extensions)) {
                            $new_media_type = 'pdf';
                        } elseif (in_array($file_extension, $ppt_extensions)) {
                            $new_media_type = 'ppt';
                        } elseif (in_array($file_extension, $pptx_extensions)) {
                            $new_media_type = 'pptx';
                        } elseif (in_array($file_extension, $doc_extensions)) {
                            $new_media_type = 'doc';
                        } elseif (in_array($file_extension, $docx_extensions)) {
                            $new_media_type = 'docx';
                        } elseif (in_array($file_extension, $xls_extensions)) {
                            $new_media_type = 'xls';
                        } elseif (in_array($file_extension, $xlsx_extensions)) {
                            $new_media_type = 'xlsx';
                        } elseif (in_array($file_extension, $txt_extensions)) {
                            $new_media_type = 'txt';
                        } elseif (in_array($file_extension, $zip_extensions)) {
                            $new_media_type = 'zip';
                        } else {
                            $new_media_type = 'other'; // Default for unknown types
                        }

                        // Delete old file if a new one was successfully uploaded and old one exists
                        if (!empty($old_media_path) && file_exists($old_media_path)) {
                            unlink($old_media_path);
                        }
                    }
                } else {
                    // If no new file, ensure the media type is kept as 'none' if path is empty
                    if (empty($new_media_path)) {
                        $new_media_type = 'none';
                    }
                }

                // Update chapter media details
                $stmt = $this->conn->prepare("
                    UPDATE chapters 
                    SET media_path = ?, 
                        media_type = ?, 
                        media_caption = ?, 
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                
                $stmt->execute([
                    $new_media_path,
                    $new_media_type,
                    $media_caption,
                    $chapter_id
                ]);

                ob_clean(); // Clean any buffer output
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Chapter media updated successfully'
                ]);
                exit();

            } catch (Exception $e) {
                ob_clean(); // Clean any buffer output
                $this->conn->rollBack(); 
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Error updating chapter media: ' . $e->getMessage()
                ]);
                exit();
            }
        }
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $course_title = $_POST['course_title'];
    $description = $_POST['description'];

    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/../../uploads/courses/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = time() . '_' . basename($_FILES['course_image']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['course_image']['tmp_name'], $file_path)) {
            $course_image = 'uploads/courses/' . $file_name;

            $controller = new ModuleController();
            if ($controller->create($course_title, $course_image, $description)) {
                header("Location: /instructor/module");
                exit();
            } else {
                $error_message = "Failed to create course.";
            }
        } else {
            $error_message = "Failed to upload image.";
        }
    } else {
        $error_message = "Please select an image.";
    }
}

// Get all courses
$controller = new ModuleController();
$courses = $controller->listCourses();
