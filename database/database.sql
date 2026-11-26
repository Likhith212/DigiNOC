CREATE DATABASE IF NOT EXISTS clearx_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clearx_db;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  identifier VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('Student','Faculty','HOD','Principal','Super Admin') NOT NULL,
  status ENUM('Active','Inactive','Locked') NOT NULL DEFAULT 'Active',
  failed_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
  locked_until DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_role (role),
  INDEX idx_users_status (status)
) ENGINE=InnoDB;

CREATE TABLE students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  usn VARCHAR(50) NOT NULL UNIQUE,
  department_id INT UNSIGNED NULL,
  semester TINYINT UNSIGNED NOT NULL,
  phone VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_students_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_students_usn (usn)
) ENGINE=InnoDB;

CREATE TABLE faculty (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  employee_id VARCHAR(50) NOT NULL UNIQUE,
  department_id INT UNSIGNED NULL,
  designation VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_faculty_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  code VARCHAR(20) NOT NULL UNIQUE,
  hod_user_id INT UNSIGNED NULL,
  mandatory TINYINT(1) NOT NULL DEFAULT 1,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_departments_active (active)
) ENGINE=InnoDB;

ALTER TABLE students ADD CONSTRAINT fk_students_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL;
ALTER TABLE faculty ADD CONSTRAINT fk_faculty_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL;
ALTER TABLE departments ADD CONSTRAINT fk_departments_hod FOREIGN KEY (hod_user_id) REFERENCES users(id) ON DELETE SET NULL;

CREATE TABLE clearance_requests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  semester TINYINT UNSIGNED NOT NULL,
  status ENUM('Pending','Partially Approved','Rejected','Approved') NOT NULL DEFAULT 'Pending',
  submitted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  completed_at DATETIME NULL,
  CONSTRAINT fk_requests_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  INDEX idx_requests_status (status)
) ENGINE=InnoDB;

CREATE TABLE department_clearances (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  request_id INT UNSIGNED NOT NULL,
  department_id INT UNSIGNED NOT NULL,
  faculty_user_id INT UNSIGNED NULL,
  status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  remarks TEXT NULL,
  updated_at DATETIME NULL,
  UNIQUE KEY uq_request_department (request_id, department_id),
  CONSTRAINT fk_dc_request FOREIGN KEY (request_id) REFERENCES clearance_requests(id) ON DELETE CASCADE,
  CONSTRAINT fk_dc_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
  CONSTRAINT fk_dc_faculty FOREIGN KEY (faculty_user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_dc_status (status)
) ENGINE=InnoDB;

CREATE TABLE approvals (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  request_id INT UNSIGNED NOT NULL,
  approver_user_id INT UNSIGNED NOT NULL,
  role ENUM('Faculty','HOD','Principal') NOT NULL,
  department_id INT UNSIGNED NULL,
  status ENUM('Approved','Rejected') NOT NULL,
  remarks TEXT NULL,
  approved_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_approvals_request FOREIGN KEY (request_id) REFERENCES clearance_requests(id) ON DELETE CASCADE,
  CONSTRAINT fk_approvals_user FOREIGN KEY (approver_user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_approvals_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  INDEX idx_approvals_request (request_id)
) ENGINE=InnoDB;

CREATE TABLE notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  message VARCHAR(500) NOT NULL,
  link VARCHAR(255) NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_notifications_unread (user_id, is_read)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  user_role VARCHAR(50) NULL,
  action VARCHAR(150) NOT NULL,
  record_id INT UNSIGNED NULL,
  details TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_audit_created (created_at),
  INDEX idx_audit_action (action),
  CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE certificates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  request_id INT UNSIGNED NOT NULL UNIQUE,
  student_id INT UNSIGNED NOT NULL,
  certificate_id VARCHAR(80) NOT NULL UNIQUE,
  issue_date DATE NOT NULL,
  verification_url VARCHAR(255) NOT NULL,
  revoked TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cert_request FOREIGN KEY (request_id) REFERENCES clearance_requests(id) ON DELETE CASCADE,
  CONSTRAINT fk_cert_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE system_settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO system_settings (setting_key, setting_value) VALUES
('college_name','CLEARX College of Engineering'),
('principal_name','Dr. A. Kumar'),
('lockout_attempts','5'),
('lockout_minutes','15');

INSERT INTO users (name, identifier, email, password_hash, role) VALUES
('Super Administrator','ADMIN001','admin@clearx.edu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Super Admin'),
('Dr. Anil Kumar','PRINCIPAL01','principal@clearx.edu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Principal'),
('Arjun Rao','1CR23CS001','arjun@clearx.edu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Student');

INSERT INTO departments (name, code, active, mandatory) VALUES
('Computer Science','CSE',1,1),
('Library','LIB',1,1),
('Accounts','ACC',1,1),
('Hostel','HOS',1,1);

UPDATE departments SET hod_user_id=(SELECT id FROM users WHERE role='Principal') WHERE id=1;

INSERT INTO students (user_id, usn, department_id, semester, phone)
VALUES ((SELECT id FROM users WHERE identifier='1CR23CS001'),'1CR23CS001',1,6,'9876543210');

INSERT INTO users (name, identifier, email, password_hash, role) VALUES
('Prof. Meera Nair','FAC001','meera@clearx.edu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Faculty'),
('Prof. Rajesh HOD','HOD001','hod.cse@clearx.edu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','HOD');

INSERT INTO faculty (user_id, employee_id, department_id, designation)
VALUES
((SELECT id FROM users WHERE identifier='FAC001'),'FAC001',1,'Assistant Professor'),
((SELECT id FROM users WHERE identifier='HOD001'),'HOD001',1,'Head of Department');

UPDATE departments SET hod_user_id=(SELECT id FROM users WHERE identifier='HOD001') WHERE code='CSE';
