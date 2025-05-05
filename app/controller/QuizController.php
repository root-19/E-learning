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
            $stmt = $this->conn->prepare("SELECT id, correct_answer FROM quizzes WHERE chapter_id = ?");
            $stmt->execute([$chapter_id]);
            $correct_answers = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

            if (empty($correct_answers)) {
                throw new \Exception('No quizzes found for this chapter');
            }

            // Calculate score
            $total_questions = count($correct_answers);
            $correct_count = 0;

            // Record each answer
            $insert_attempt = $this->conn->prepare("
                INSERT INTO quiz_attempts (user_id, quiz_id, selected_answer, is_correct) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($answers as $quiz_id => $selected_answer) {
                if (!isset($correct_answers[$quiz_id])) continue;

                $is_correct = $selected_answer === $correct_answers[$quiz_id];
                if ($is_correct) $correct_count++;

                $insert_attempt->execute([
                    $user_id,
                    $quiz_id,
                    $selected_answer,
                    $is_correct
                ]);
            }

            $score = ($correct_count / $total_questions) * 100;

            $this->conn->commit();

            echo json_encode([
                'success' => true,
                'score' => round($score, 1),
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
} 