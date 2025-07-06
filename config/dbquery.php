<?php

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    department VARCHAR(100),
    batch VARCHAR(20),
    student_id VARCHAR(20) UNIQUE
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100),
    course_code VARCHAR(20) UNIQUE
);

CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    semester VARCHAR(20),
    grade VARCHAR(5),
    FOREIGN KEY(student_id) REFERENCES students(id),
    FOREIGN KEY(course_id) REFERENCES courses(id)
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    date DATE,
    status ENUM('Present', 'Absent'),
    FOREIGN KEY(student_id) REFERENCES students(id),
    FOREIGN KEY(course_id) REFERENCES courses(id)
);

CREATE TABLE advisors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    advisor_name VARCHAR(100),
    FOREIGN KEY(student_id) REFERENCES students(id)
);

?>