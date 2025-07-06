<?php
include '../config/db.php';

// Get students
$students = $conn->query("SELECT * FROM students ORDER BY name ASC");

// Handle form submission
if (isset($_POST['submit'])) {
    $student_id = $_POST['student_id'];
    $advisor_name = trim($_POST['advisor_name']);
    
    // Validation
    $errors = [];
    if (empty($student_id)) $errors[] = "Please select a student";
    if (empty($advisor_name)) $errors[] = "Advisor name is required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO advisors (student_id, advisor_name) VALUES (?, ?)");
        $stmt->bind_param("is", $student_id, $advisor_name);
        
        if ($stmt->execute()) {
            $success = "Advisor assigned successfully!";
            $_POST = []; // Clear form
        } else {
            $errors[] = "Error assigning advisor: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Advisor</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="page-title">👨‍🏫 Assign Advisor</h2>
        
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
                <label for="advisor_name" class="form-label">Advisor Name</label>
                <input type="text" id="advisor_name" name="advisor_name" class="form-control" 
                       value="<?= htmlspecialchars($_POST['advisor_name'] ?? '') ?>" required
                       placeholder="Enter advisor's full name">
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary btn-block">Assign Advisor</button>
        </form>
        
        <!-- <a href="list.php" class="back-link">← Back to Advisor List</a> -->
    </div>
</body>
</html>