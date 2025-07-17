<?php
include '../config/db.php';

// Fetch all students
$students = $conn->query("SELECT * FROM students ORDER BY name ASC");

// Fetch transcript if student selected
$transcript = [];
$student_info = [];
if (isset($_GET['student_id'])) {
    $sid = $_GET['student_id'];
    
    // Get student info
    $student_result = $conn->query("SELECT * FROM students WHERE id = $sid");
    $student_info = $student_result->fetch_assoc();
    
    // Get transcript data
    $transcript_result = $conn->query("SELECT e.*, c.course_name, c.course_code
                                     FROM enrollments e
                                     JOIN courses c ON e.course_id = c.id
                                     WHERE e.student_id = $sid
                                     ORDER BY e.semester DESC, c.course_name ASC");
    $transcript = $transcript_result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Transcript</title>
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
        
        .page-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }
        
        .form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
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
            max-width: 400px;
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
        
        .transcript-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .student-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .student-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .student-id {
            color: #666;
            font-size: 1rem;
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
        
        .grade-A { color: var(--success-color); font-weight: 500; }
        .grade-B { color: #3498db; font-weight: 500; }
        .grade-C { color: #f39c12; font-weight: 500; }
        .grade-D { color: #e67e22; font-weight: 500; }
        .grade-F { color: var(--accent-color); font-weight: 500; }
        
        .no-results {
            padding: 2rem;
            text-align: center;
            color: #666;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .styled-table {
                display: block;
                overflow-x: auto;
            }
            
            .form-control {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="form-container">
            <h2 class="page-title">📝 Student Transcript</h2>
            
            <form method="get">
                <div class="form-group">
                    <label for="student_id" class="form-label">Select Student</label>
                    <select id="student_id" name="student_id" class="form-control" required>
                        <option value="">-- Select a Student --</option>
                        <?php while ($s = $students->fetch_assoc()) : ?>
                            <option value="<?= $s['id'] ?>" <?= isset($_GET['student_id']) && $_GET['student_id'] == $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['student_id']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">View Transcript</button>
                <a href="../index.php" class="back-link">← Back to Home Page</a>
            </form>
        </div>
        
        <?php if (isset($_GET['student_id'])): ?>
            <div class="transcript-container">
                <?php if (!empty($student_info)): ?>
                    <div class="student-header">
                        <div class="student-name"><?= htmlspecialchars($student_info['name']) ?></div>
                        <div class="student-id">Student ID: <?= htmlspecialchars($student_info['student_id']) ?></div>
                        <div class="student-id">Department: <?= htmlspecialchars($student_info['department']) ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($transcript)): ?>
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Semester</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transcript as $row): 
                                $grade_class = 'grade-' . strtoupper(substr($row['grade'], 0, 1));
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['course_code']) ?></td>
                                    <td><?= htmlspecialchars($row['course_name']) ?></td>
                                    <td><?= htmlspecialchars($row['semester']) ?></td>
                                    <td class="<?= $grade_class ?>"><?= htmlspecialchars($row['grade']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-results">
                        No transcript records found for this student.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>