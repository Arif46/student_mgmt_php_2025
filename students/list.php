<?php
include '../config/db.php';

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM students WHERE 
            name LIKE '%$search%' OR 
            department LIKE '%$search%' OR 
            batch LIKE '%$search%' OR 
            student_id LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM students";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">🎓 Student List</h2>
            <a href="../index.php" class="btn btn-secondary">🏠 Go to Home</a>
        </div>
        <!-- <h2 class="page-title">🎓 Student List</h2> -->

        <form method="get" class="form-inline">
            <input type="text" name="search" class="form-control" placeholder="Search by name, dept, batch, ID" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn">🔍 Search</button>
            <a href="add.php" class="btn btn-secondary">➕ Add Student</a>
        </form>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Batch</th>
                    <th>Student ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td><?= htmlspecialchars($row['batch']) ?></td>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
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