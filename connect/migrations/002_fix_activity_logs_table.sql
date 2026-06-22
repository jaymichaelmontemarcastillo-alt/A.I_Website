-- ═══════════════════════════════════════════════════════════════════════════
-- ACTIVITY LOGS TABLE FIX
-- This migration fixes the activity_logs table by adding PRIMARY KEY 
-- and AUTO_INCREMENT to LogID column
-- ═══════════════════════════════════════════════════════════════════════════

-- Step 1: Backup existing data (if needed)
-- SELECT * INTO OUTFILE '/tmp/activity_logs_backup.csv' 
-- FROM activity_logs;

-- Step 2: Drop the existing table constraint/recreate
-- First, check if LogID is already a primary key
-- If not, run these commands:

-- Alter the table to add PRIMARY KEY and AUTO_INCREMENT
ALTER TABLE `activity_logs` 
MODIFY COLUMN `LogID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- Step 3: Verify the fix
-- SELECT * FROM activity_logs ORDER BY LogID DESC LIMIT 10;

-- Step 4: Optional - Reset AUTO_INCREMENT to next available value
-- ALTER TABLE `activity_logs` AUTO_INCREMENT = (SELECT MAX(LogID) + 1 FROM (SELECT MAX(LogID) AS LogID FROM activity_logs) AS t);

-- ═══════════════════════════════════════════════════════════════════════════
-- Alternative: Complete table recreation (if above doesn't work)
-- ═══════════════════════════════════════════════════════════════════════════

-- Uncomment and run ONLY if the above fails:
/*
-- Create backup of existing data
CREATE TABLE `activity_logs_backup` AS SELECT * FROM `activity_logs`;

-- Drop the old table
DROP TABLE `activity_logs`;

-- Create new table with proper structure
CREATE TABLE `activity_logs` (
  `LogID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `UserID` INT(11) DEFAULT NULL,
  `UserName` VARCHAR(100) NOT NULL,
  `ActionDetails` TEXT NOT NULL,
  `ReferenceID` VARCHAR(50) DEFAULT NULL,
  `ActionType` VARCHAR(50) DEFAULT NULL,
  `Status` ENUM('Success', 'Failed') NOT NULL,
  `CreatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_created_at` (`CreatedAt`),
  KEY `idx_user_id` (`UserID`),
  KEY `idx_status` (`Status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Restore data
INSERT INTO `activity_logs` 
(UserID, UserName, ActionDetails, ReferenceID, ActionType, Status, CreatedAt)
SELECT UserID, UserName, ActionDetails, ReferenceID, ActionType, Status, CreatedAt
FROM `activity_logs_backup`
WHERE CreatedAt IS NOT NULL
ORDER BY CreatedAt ASC;

-- Optional: Drop backup table
-- DROP TABLE `activity_logs_backup`;
*/
