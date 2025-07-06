<?php
include '../config/db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM courses WHERE id = $id");
$row = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['submit'])) {
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    
    // Basic validation
    $errors = [];
    if (empty($course_name)) $errors[] = "Course name is required";
    if (empty($course_code)) $errors[] = "Course code is required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE courses SET course_name=?, course_code=? WHERE id=?");
        $stmt->bind_param("ssi", $course_name, $course_code, $id);
        
        if ($stmt->execute()) {
            header("Location: list.php?success=updated");
            exit();
        } else {
            $errors[] = "Error updating course: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
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
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #1a252f;
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
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="page-title">✏️ Edit Course</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul style="margin-left: 1.5rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" id="course_name" name="course_name" class="form-control" 
                       value="<?= htmlspecialchars($row['course_name']) ?>" required
                       placeholder="Enter course name">
            </div>
            
            <div class="form-group">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" id="course_code" name="course_code" class="form-control" 
                       value="<?= htmlspecialchars($row['course_code']) ?>" required
                       placeholder="Enter course code">
            </div>
            
            <div class="action-buttons">
                <button type="submit" name="submit" class="btn btn-primary">Update Course</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>