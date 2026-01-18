-- Add missing fields to projects table
USE nizaam;

ALTER TABLE projects 
ADD COLUMN start_date DATE NULL AFTER status,
ADD COLUMN end_date DATE NULL AFTER start_date,
ADD COLUMN budget DECIMAL(15,2) NULL AFTER end_date;
