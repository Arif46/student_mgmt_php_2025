<?php
include '../config/db.php';

// Fetch student and course list
$students = $conn->query("SELECT * FROM students ORDER BY name ASC");
$courses = $conn->query("SELECT * FROM courses ORDER BY course_name ASC");

// Mark Attendance
if (isset($_POST['submit'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    
    // Validation
    $errors = [];
    if (empty($student_id)) $errors[] = "Please select a student";
    if (empty($course_id)) $errors[] = "Please select a course";
    if (empty($date)) $errors[] = "Date is required";
    if (empty($status)) $errors[] = "Status is required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, course_id, date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $course_id, $date, $status);
        
        if ($stmt->execute()) {
            $success = "Attendance recorded successfully!";
            $_POST = []; // Clear form
        } else {
            $errors[] = "Error recording attendance: " . $conn->error;
        }
    }
}

// View Attendance
$attendanceData = [];
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    $result = $conn->query("SELECT a.*, c.course_name FROM attendance a 
                            JOIN courses c ON a.course_id = c.id 
                            WHERE a.student_id = $student_id 
                            ORDER BY a.date DESC");
    $attendanceData = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --success-color: #2ecc71;
            --absent-color: #f39c12;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .page-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }
        
        .section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px 12px;
            padding-right: 40px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .styled-table thead tr {
            background-color: var(--primary-color);
            color: white;
            text-align: left;
        }
        
        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }
        
        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
            transition: background-color 0.2s;
        }
        
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }
        
        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid var(--primary-color);
        }
        
        .styled-table tbody tr:hover {
            background-color: #e3f2fd;
        }
        
        .status-present {
            color: var(--success-color);
            font-weight: 500;
        }
        
        .status-absent {
            color: var(--absent-color);
            font-weight: 500;
        }
        
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .styled-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="section">
            <h2 class="section-title">📅 Mark Attendance</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul style="margin-left: 1.5rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="student_id" class="form-label">Student</label>
                    <select id="student_id" name="student_id" class="form-control" required>
                        <option value="">-- Select Student --</option>
                        <?php while ($s = $students->fetch_assoc()): ?>
                            <option value="<?= $s['id'] ?>" <?= isset($_POST['student_id']) && $_POST['student_id'] == $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['student_id']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="course_id" class="form-label">Course</label>
                    <select id="course_id" name="course_id" class="form-control" required>
                        <option value="">-- Select Course --</option>
                        <?php while ($c = $courses->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>" <?= isset($_POST['course_id']) && $_POST['course_id'] == $c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['course_name']) ?> (<?= htmlspecialchars($c['course_code']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" id="date" name="date" class="form-control" 
                           value="<?= htmlspecialchars($_POST['date'] ?? date('Y-m-d')) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="Present" <?= isset($_POST['status']) && $_POST['status'] == 'Present' ? 'selected' : '' ?>>Present</option>
                        <option value="Absent" <?= isset($_POST['status']) && $_POST['status'] == 'Absent' ? 'selected' : '' ?>>Absent</option>
                    </select>
                </div>
                
                <button type="submit" name="submit" class="btn btn-primary btn-block">Mark Attendance</button>
            </form>
        </div>
        
        <div class="section">
            <h2 class="section-title">📊 Attendance History</h2>
            
            <form method="get" class="form-inline" style="margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="view_student_id" class="form-label">Select Student</label>
                    <select id="view_student_id" name="student_id" class="form-control">
                        <option value="">-- Select Student --</option>
                        <?php
                        $students2 = $conn->query("SELECT * FROM students ORDER BY name ASC");
                        while ($s = $students2->fetch_assoc()): ?>
                            <option value="<?= $s['id'] ?>" <?= isset($_GET['student_id']) && $_GET['student_id'] == $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['student_id']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">View Attendance</button>
                <a href="../index.php" class="back-link">← Back to Home Page</a>
            </form>
            
            <?php if (!empty($attendanceData)): ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendanceData as $att): ?>
                        <tr>
                            <td><?= date('M j, Y', strtotime($att['date'])) ?></td>
                            <td><?= htmlspecialchars($att['course_name']) ?></td>
                            <td class="<?= $att['status'] == 'Present' ? 'status-present' : 'status-absent' ?>">
                                <?= htmlspecialchars($att['status']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (isset($_GET['student_id'])): ?>
                <div class="alert alert-danger">
                    No attendance records found for this student.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>