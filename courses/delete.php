
<?php
include '../config/db.php';

// Check if ID exists
if (!isset($_GET['id'])) {
    header("Location: list.php?error=no_id");
    exit();
}

$id = $_GET['id'];

// Fetch student data for confirmation message
$result = $conn->query("SELECT * FROM courses WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: list.php?error=not_found");
    exit();
}
$course = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        // Perform deletion
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            header("Location: list.php?delete=success");
            exit();
        } else {
            $error = "Failed to delete student. Please try again.";
        }
    } else {
        // User canceled - redirect back
        header("Location: list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Courses</title>
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
        
        .confirmation-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .page-title {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .confirmation-message {
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .student-info {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 6px;
            margin-bottom: 2rem;
            text-align: left;
        }
        
        .student-info p {
            margin-bottom: 0.5rem;
        }
        
        .student-info strong {
            color: var(--dark-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
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
        
        .btn-danger {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #1a252f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <div class="confirmation-container">
        <h2 class="page-title">⚠️ Delete Course</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="confirmation-message">
            Are you sure you want to permanently delete this course record?
        </div>
        
        <div class="student-info">
            <p><strong>Course Name:</strong> <?= htmlspecialchars($course['course_name']) ?></p>
            <p><strong>Course Code:</strong> <?= htmlspecialchars($course['course_code']) ?></p>
        </div>
        
        <form method="post">
            <div class="action-buttons">
                <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete Permanently</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>