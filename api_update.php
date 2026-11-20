<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Department of Electronics and Computer Engineering
// Live Monitoring API Endpoint for Smart Lab System

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db.php';

// Handle Live Status Update from Python Agent or Student Portal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);

    // Support both raw JSON and form-urlencoded POST
    if (!$input) {
        $input = $_POST;
    }

    $roll_no = trim($input['roll_no'] ?? '');
    $status = trim($input['status'] ?? '');
    $current_window = trim($input['current_window'] ?? '');

    if (empty($roll_no)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Missing required parameter: roll_no"
        ]);
        exit();
    }

    // Default values if empty
    if (empty($status)) {
        $status = 'Active';
    }
    if (empty($current_window)) {
        $current_window = 'None';
    }

    // Check if student exists
    $check_stmt = $conn->prepare("SELECT id FROM student_users WHERE roll_no = ?");
    $check_stmt->bind_param("s", $roll_no);
    $check_stmt->execute();
    $res = $check_stmt->get_result();

    if ($res->num_rows === 0) {
        $check_stmt->close();
        echo json_encode([
            "success" => false,
            "message" => "Student roll number '{$roll_no}' not registered. Please log in via student_login.php first.",
            "roll_no" => $roll_no
        ]);
        exit();
    }
    $check_stmt->close();

    // Update matching record in student_users via prepared statement
    $update_stmt = $conn->prepare("UPDATE student_users SET status = ?, current_window = ?, last_updated = CURRENT_TIMESTAMP WHERE roll_no = ?");
    $update_stmt->bind_param("sss", $status, $current_window, $roll_no);

    if ($update_stmt->execute()) {
        echo json_encode([
            "success"        => true,
            "message"        => "Record updated successfully",
            "roll_no"        => $roll_no,
            "status"         => $status,
            "current_window" => $current_window,
            "timestamp"      => date('Y-m-d H:i:s')
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Database execution failed: " . $conn->error
        ]);
    }
    $update_stmt->close();
    exit();
}

// Handle GET request: return all students for dashboard live sync
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT id, student_name, roll_no, pc_number, status, current_window, last_updated FROM student_users ORDER BY id DESC");
    $students = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }

    echo json_encode([
        "success"   => true,
        "count"     => count($students),
        "students"  => $students,
        "timestamp" => date('Y-m-d H:i:s')
    ]);
    exit();
}

http_response_code(405);
echo json_encode(["success" => false, "message" => "Method not allowed"]);
?>
