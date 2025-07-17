<?php
include '../config/db.php';

// Fetch students and courses
$students = $conn->query("SELECT * FROM students ORDER BY name ASC");
$courses = $conn->query("SELECT * FROM courses ORDER BY course_name ASC");

// Handle form submission
if (isset($_POST['submit'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $semester = trim($_POST['semester']);
    $grade = trim($_POST['grade']);
    
    // Validation
    $errors = [];
    if (empty($student_id)) $errors[] = "Please select a student";
    if (empty($course_id)) $errors[] = "Please select a course";
    if (empty($semester)) $errors[] = "Semester is required";
    if (empty($grade)) $errors[] = "Grade is required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id, semester, grade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $course_id, $semester, $grade);
        
        if ($stmt->execute()) {
            $success = "Student enrolled successfully!";
            $_POST = []; // Clear form
        } else {
            $errors[] = "Error enrolling student: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --success-color: #2ecc71;
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
        
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .page-title {
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
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
        
        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .grade-hint {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="page-title">📝 Enroll Student</h2>
        
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
                <label for="semester" class="form-label">Semester</label>
                <input type="text" id="semester" name="semester" class="form-control" 
                       value="<?= htmlspecialchars($_POST['semester'] ?? '') ?>" required
                       placeholder="e.g. Fall 2023">
            </div>
            
            <div class="form-group">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" id="grade" name="grade" class="form-control" 
                       value="<?= htmlspecialchars($_POST['grade'] ?? '') ?>" required
                       placeholder="e.g. A, B+, C-">
                <div class="grade-hint">Enter letter grade (A, B, C, etc.) with optional +/-</div>
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary btn-block">Enroll Student</button>
        </form>
        
        <a href="../index.php" class="back-link">← Back to Home Page</a>
    </div>
</body>
</html>