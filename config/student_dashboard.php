SELECT l.*, m.name as meal_name 
FROM meal_logs l 
JOIN meal_periods m ON l.meal_period_id = m.id 
WHERE l.student_id = :student_db_id 
ORDER BY l.timestamp DESC