<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../../config/database.php';

class QuizController {
    private $conn;

    public function __construct() {
        $this->conn = \Database::connect();
    }

    public function submitQuiz() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'), true);
        $chapter_id = $data['chapter_id'] ?? 0;
        $answers = $data['answers'] ?? [];
        $user_id = $_SESSION['user_id'];

        try {
            $this->conn->beginTransaction();

            // Get correct answers
            $stmt = $this->conn->prepare("SELECT id, correct_answer, quiz_type FROM quizzes WHERE chapter_id = ?");
            $stmt->execute([$chapter_id]);
            $quizzes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($quizzes)) {
                throw new \Exception('No quizzes found for this chapter');
            }

            // Calculate score
            $total_questions = count($quizzes);
            $correct_count = 0;

            // Record each answer
            $insert_attempt = $this->conn->prepare("
                INSERT INTO quiz_attempts (user_id, quiz_id, selected_answer, is_correct) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($quizzes as $quiz) {
                $quiz_id = $quiz['id'];
                $selected_answer = $answers[$quiz_id] ?? null;
                
                if ($selected_answer === null) continue;

                $is_correct = false;
                
                // Handle different quiz types
                switch ($quiz['quiz_type']) {
                    case 'fill_blank':
                        // For fill in the blank, do case-insensitive comparison
                        $is_correct = strtolower(trim($selected_answer)) === strtolower(trim($quiz['correct_answer']));
                        break;
                        
                    case 'true_false':
                        // For true/false, convert to uppercase for storage and comparison
                        $selected_answer = strtoupper($selected_answer);
                        $is_correct = $selected_answer === strtoupper($quiz['correct_answer']);
                        break;
                        
                    default: // multiple choice
                        // For multiple choice, convert to uppercase for storage
                        $selected_answer = strtoupper($selected_answer);
                        $is_correct = $selected_answer === strtoupper($quiz['correct_answer']);
                        break;
                }

                if ($is_correct) $correct_count++;

                $insert_attempt->execute([
                    $user_id,
                    $quiz_id,
                    $selected_answer,
                    $is_correct
                ]);
            }

            // Remove percentage calculation and just use raw score
            $score = $correct_count;

            $this->conn->commit();

            echo json_encode([
                'success' => true,
                'score' => $score,
                'correct' => $correct_count,
                'total' => $total_questions
            ]);

        } catch (\Exception $e) {
            $this->conn->rollBack();
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit quiz: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    public function updateQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_reporting(0); // Temporarily disable error reporting
            ob_start(); // Start output buffering

            try {
                $quiz_id = $_POST['quiz_id'];
                $question = $_POST['question'];
                $option_a = $_POST['option_a'];
                $option_b = $_POST['option_b'];
                $option_c = $_POST['option_c'];
                $option_d = $_POST['option_d'];
                $correct_answer = $_POST['correct_answer'];

                $this->conn->beginTransaction();

                $stmt = $this->conn->prepare("
                    UPDATE quizzes 
                    SET question = ?, 
                        option_a = ?, 
                        option_b = ?, 
                        option_c = ?, 
                        option_d = ?, 
                        correct_answer = ?, 
                        updated_at = CURRENT_TIMESTAMP 
                    WHERE id = ?
                ");

                $stmt->execute([
                    $question,
                    $option_a,
                    $option_b,
                    $option_c,
                    $option_d,
                    $correct_answer,
                    $quiz_id
                ]);

                $this->conn->commit();

                ob_clean(); // Clean any buffer output
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Quiz updated successfully'
                ]);
                exit();

            } catch (\Exception $e) {
                ob_clean(); // Clean any buffer output
                $this->conn->rollBack();
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Error updating quiz: ' . $e->getMessage()
                ]);
                exit();
            }
        }
    }
} 