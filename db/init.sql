-- Create table
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    position VARCHAR(50),
    salary DECIMAL(10,2)
);

-- Insert sample data
INSERT INTO employees (name, position, salary) VALUES
('Ali Khan', 'Software Engineer', 120000.00),
('Sara Ahmed', 'Project Manager', 150000.00),
('Bilal Iqbal', 'QA Tester', 90000.00);
