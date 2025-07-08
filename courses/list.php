<?php
include '../config/db.php';
$result = $conn->query("SELECT * FROM courses");

// Check for success/error messages
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link rel="stylesheet" href="../style.css">
    <!-- <style>
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
        
        .page-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .page-title {
            color: var(--secondary-color);
            font-size: 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
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
        
        .btn-danger {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-small {
            padding: 6px 12px;
            font-size: 0.9rem;
        }
        
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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
        
        .actions {
            display: flex;
            gap: 8px;
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
        
        @media (max-width: 768px) {
            .styled-table {
                display: block;
                overflow-x: auto;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style> -->
</head>
<body>
    <div class="page-container">
        <!-- <div class="page-header">
            <h2>📚 All Courses</h2>
            <a href="add.php" class="btn btn-primary">+ Add New Course</a>
        </div> -->
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">🎓 All Courses</h2>
            <a href="../index.php" class="btn btn-secondary">🏠 Go to Home</a>
        </div>
        <div method="get" class="form-inline">
            <a href="add.php" class="btn btn-primary">➕ Add New Course</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php 
                switch($success) {
                    case 'added': echo "Course added successfully!"; break;
                    case 'updated': echo "Course updated successfully!"; break;
                    case 'deleted': echo "Course deleted successfully!"; break;
                    default: echo "Operation completed successfully!";
                }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php 
                switch($error) {
                    case 'delete_failed': echo "Failed to delete course. Please try again."; break;
                    default: echo "An error occurred. Please try again.";
                }
                ?>
            </div>
        <?php endif; ?>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['course_name']) ?></td>
                    <td><?= htmlspecialchars($row['course_code']) ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-small">✏️ Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-small">🗑 Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>