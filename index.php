<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Management System</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            text-align: center;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-align: center;
            border-left: 5px solid var(--primary-color);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card a {
            text-decoration: none;
            color: var(--dark-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }
        
        .card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .card h2 {
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }
        
        .card p {
            color: #666;
        }
        
        footer {
            text-align: center;
            margin-top: 3rem;
            padding: 1rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Student Information Management System</h1>
            <p class="subtitle">Efficient Student Data Management</p>
        </div>
    </header>
    
    <div class="container">
        <div class="dashboard">
            <div class="card">
                <a href="students/list.php">
                    <i class="fas fa-users"></i>
                    <h2>Students</h2>
                    <p>Manage student records, profiles, and personal information</p>
                </a>
            </div>
            
            <div class="card">
                <a href="courses/list.php">
                    <i class="fas fa-book-open"></i>
                    <h2>Courses</h2>
                    <p>View and manage all available courses and curriculum</p>
                </a>
            </div>
            
            <div class="card">
                <a href="academics/assign_course.php">
                    <i class="fas fa-clipboard-list"></i>
                    <h2>Enrollments</h2>
                    <p>Handle course enrollments and class registrations</p>
                </a>
            </div>
            
            <div class="card">
                <a href="academics/assign_advisor.php">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h2>Advisors</h2>
                    <p>Assign and manage faculty advisors for students</p>
                </a>
            </div>
            
            <div class="card">
                <a href="academics/attendance.php">
                    <i class="fas fa-calendar-check"></i>
                    <h2>Attendance</h2>
                    <p>Track and manage student attendance records</p>
                </a>
            </div>
            
            <div class="card">
                <a href="academics/transcripts.php">
                    <i class="fas fa-file-alt"></i>
                    <h2>Transcripts</h2>
                    <p>Generate and view academic transcripts and reports</p>
                </a>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 Student Information Management System | University Lab Project</p>
        </div>
    </footer>
</body>
</html>