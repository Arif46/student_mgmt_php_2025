<?php 
include '../config/db.php';

// Handle form submission
if (isset($_POST['submit'])) {
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    
    // Basic validation
    $errors = [];
    if (empty($course_name)) $errors[] = "Course name is required";
    if (empty($course_code)) $errors[] = "Course code is required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code) VALUES (?, ?)");
        $stmt->bind_param("ss", $course_name, $course_code);
        
        if ($stmt->execute()) {
            $success = "Course added successfully!";
            // Clear form fields
            $_POST = [];
        } else {
            $errors[] = "Error adding course: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
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
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="page-title">📚 Add New Course</h2>
        
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
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" id="course_name" name="course_name" class="form-control" 
                       value="<?= htmlspecialchars($_POST['course_name'] ?? '') ?>" required
                       placeholder="e.g. Introduction to Computer Science">
            </div>
            
            <div class="form-group">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" id="course_code" name="course_code" class="form-control" 
                       value="<?= htmlspecialchars($_POST['course_code'] ?? '') ?>" required
                       placeholder="e.g. CS101">
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary btn-block">Add Course</button>
        </form>
        
        <a href="list.php" class="back-link">← Back to Course List</a>
    </div>
</body>
</html>