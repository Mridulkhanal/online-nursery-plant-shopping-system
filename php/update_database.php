<?php
include 'db_connect.php';

$schema_updates = "
-- Create categories table if it doesn't exist
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ensure products table uses InnoDB engine
ALTER TABLE products ENGINE=InnoDB;

-- Add category_id column if it doesn't exist
ALTER TABLE products
ADD COLUMN IF NOT EXISTS category_id INT;

-- Check if the foreign key constraint already exists
SET @constraint_exists = (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'products'
    AND CONSTRAINT_NAME = 'fk_category'
);

-- Add foreign key constraint if it doesn't exist
SET @sql = IF(
    @constraint_exists = 0,
    'ALTER TABLE products ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL',
    'SELECT \"Constraint fk_category already exists\"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add response and responded_at to messages if they don't exist
ALTER TABLE messages
ADD COLUMN IF NOT EXISTS response TEXT,
ADD COLUMN IF NOT EXISTS responded_at TIMESTAMP NULL;
";

try {
    $conn->multi_query($schema_updates);
    do {
        $conn->store_result();
    } while ($conn->next_result());
    echo "Database updated successfully!";
    
    // Populate categories if empty
    $result = $conn->query("SELECT COUNT(*) as count FROM categories");
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO categories (name) VALUES ('Indoor'), ('Outdoor'), ('Flowering') ");
        echo " Sample categories added.";
    }
} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage();
}
$conn->close();
?>
