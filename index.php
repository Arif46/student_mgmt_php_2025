<!DOCTYPE html>
<html>
<head>
    <title>Student Information Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Student Information Management System</h1>

<div class="tab-menu">
    <a href="#students">👨‍🎓 Students</a>
    <a href="#courses">📚 Courses</a>
    <a href="#enroll">📝 Enrollments</a>
    <a href="#advisors">👨‍🏫 Advisors</a>
    <a href="#attendance">🗓 Attendance</a>
    <a href="#transcripts">📄 Transcripts</a>
</div>

<div class="tab-content">
    <div id="students" class="tab-section">
        <h2>Manage Students</h2>
        <p>Add, update, view or delete student records.</p>
        <a href="students/list.php" class="button">Go to Students</a>
    </div>

    <div id="courses" class="tab-section">
        <h2>Manage Courses</h2>
        <p>Create, update, or delete courses.</p>
        <a href="courses/list.php" class="button">Go to Courses</a>
    </div>

    <div id="enroll" class="tab-section">
        <h2>Enroll Students</h2>
        <p>Assign students to courses and set grades.</p>
        <a href="academics/assign_course.php" class="button">Enroll Students</a>
    </div>

    <div id="advisors" class="tab-section">
        <h2>Assign Advisors</h2>
        <p>Assign academic advisors to students.</p>
        <a href="academics/assign_advisor.php" class="button">Assign Advisors</a>
    </div>

    <div id="attendance" class="tab-section">
        <h2>Attendance</h2>
        <p>Mark and track student attendance.</p>
        <a href="academics/attendance.php" class="button">Manage Attendance</a>
    </div>

    <div id="transcripts" class="tab-section">
        <h2>View Transcripts</h2>
        <p>View student academic history and results.</p>
        <a href="academics/transcripts.php" class="button">View Transcripts</a>
    </div>
</div>

</body>
</html>
