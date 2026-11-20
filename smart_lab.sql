-- Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
-- Department of Electronics and Computer Engineering
-- Smart Digital Lab Monitoring System Table Schema
-- Compatible with InfinityFree, cPanel, and Localhost XAMPP

CREATE TABLE IF NOT EXISTS `faculty_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `faculty_name` VARCHAR(100) NOT NULL UNIQUE,
  `subject` VARCHAR(150) NOT NULL,
  `lab_room` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_name` VARCHAR(100) NOT NULL,
  `roll_no` VARCHAR(50) NOT NULL UNIQUE,
  `pc_number` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Active',
  `current_window` VARCHAR(255) DEFAULT 'None',
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- PRE-LOADED FACULTY ACCOUNTS (Universal Password: Zcoer@123)
-- ============================================================================
INSERT INTO `faculty_users` (`faculty_name`, `subject`, `lab_room`, `password`) VALUES
('Dr. Sumit R. Patil', 'DEM (Digital Electronics & Microcontroller)', 'C5-08', '$2y$10$7iXaYiRVKYxKqzDzM9D01.t1SIVPMhdswdjDIG4cD8FYFyG5JlIF2'),
('Ms. Supriya Patil', 'DSA (Data Structures & Algorithms)', 'C5-11', '$2y$10$YfJ2QuGE17gNvycdlXF9oe059wZ/L8G9tgd9VXWXOYb.xW5TDegA.'),
('Mr. Mukesh Patil', 'COA (Computer Organization & Architecture)', 'C5-10', '$2y$10$hrjXMTbbAWbGJPplpdDHLOmFXS9xHyfs4mRfaQCUcSVu1.3IV6RkS'),
('Ms. Tejaswini Pawar', 'EM3 (Engineering Mathematics III)', 'C5-10', '$2y$10$w8kM0lD32rBw4p1n8oW.g.Oq4h9K1pY2e3w5r7t8y9u0i1o2p3a4s'),
('Ms. Sampada Gaikwad', 'OE-1 (Open Elective 1)', 'C5-09', '$2y$10$0ixHKPYLaif/WnO3VwfPx.ZfH3fwVGa1P0rwrrV.WY.FDiL3DJUBm'),
('Mr. Pravin Sangle', 'EW (Electronic Workshop)', 'C3-09', '$2y$10$MJnTikLrvGT6.6xmZsfNaOoGJhirF.wFdTJTnO1E.50B/9Gp9HyGi'),
('Ms. Basamma Math', 'PST1 (Problem Solving Techniques 1)', 'C3-03', '$2y$10$W5c3JjtJjZu4qdxVR1.fCuZFJzP4tYLjJwXpUfAiSLB05ivHnCj16'),
('Ms. Sana Metkari', 'PBL (Project Based Learning)', 'C3-04', '$2y$10$3VX8LkEmbqHDqQYavIJmiOdxahraUcP4UIJpjY5pL2i1JgadNcPPu')
ON DUPLICATE KEY UPDATE `subject`=VALUES(`subject`), `lab_room`=VALUES(`lab_room`), `password`=VALUES(`password`);

-- ============================================================================
-- PRE-LOADED STUDENT ACCOUNTS (Universal Password: Student@123)
-- ============================================================================
INSERT INTO `student_users` (`student_name`, `roll_no`, `pc_number`, `password`, `status`, `current_window`) VALUES
('Rutuja Kadam', 'EC2203', 'PC-03', '$2y$10$JjV7O2nTEBeS.6yn2GrU6ePK2Hx2RO4rStLQqd3EGZiRcF78ynG8y', 'Active', 'Visual Studio Code'),
('Gayatri Adgaonkar', 'EC2205', 'PC-02', '$2y$10$JjV7O2nTEBeS.6yn2GrU6ePK2Hx2RO4rStLQqd3EGZiRcF78ynG8y', 'Active', 'None'),
('Aryan Thombare', 'EC2204', 'PC-05', '$2y$10$JjV7O2nTEBeS.6yn2GrU6ePK2Hx2RO4rStLQqd3EGZiRcF78ynG8y', 'Active', 'None'),
('Yash Kotkar', 'EC2220', 'PC-04', '$2y$10$JjV7O2nTEBeS.6yn2GrU6ePK2Hx2RO4rStLQqd3EGZiRcF78ynG8y', 'Active', 'None'),
('Amit Deshmukh', 'EC2245', 'PC-07', '$2y$10$JjV7O2nTEBeS.6yn2GrU6ePK2Hx2RO4rStLQqd3EGZiRcF78ynG8y', 'Active', 'Visual Studio Code - lab3_pbl.py')
ON DUPLICATE KEY UPDATE `student_name`=VALUES(`student_name`), `pc_number`=VALUES(`pc_number`), `password`=VALUES(`password`);
