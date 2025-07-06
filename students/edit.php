<?php 
include '../config/db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM students WHERE id = $id");
$row = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $batch = trim($_POST['batch']);
    $student_id = trim($_POST['student_id']);
    
    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($department)) $errors[] = "Department is required";
    if (empty($batch)) $errors[] = "Batch is required";
    if (empty($student_id)) $errors[] = "Student ID is required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE students SET name=?, department=?, batch=?, student_id=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $department, $batch, $student_id, $id);
        
        if ($stmt->execute()) {
            header("Location: list.php?update=success");
            exit();
        } else {
            $errors[] = "Error updating student: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
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
        
        .btn-danger {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="page-title">✏️ Edit Student</h2>
        
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
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="<?= htmlspecialchars($row['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="department" class="form-label">Department</label>
                <input type="text" id="department" name="department" class="form-control" 
                       value="<?= htmlspecialchars($row['department']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="batch" class="form-label">Batch</label>
                <input type="text" id="batch" name="batch" class="form-control" 
                       value="<?= htmlspecialchars($row['batch']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" id="student_id" name="student_id" class="form-control" 
                       value="<?= htmlspecialchars($row['student_id']) ?>" required>
            </div>
            
            <div class="action-buttons">
                <button type="submit" name="submit" class="btn btn-primary">Update Student</button>
                <a href="list.php" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>