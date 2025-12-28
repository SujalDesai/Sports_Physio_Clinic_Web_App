CREATE DATABASE IF NOT EXISTS football_analytics;
USE football_analytics;


CREATE TABLE IF NOT EXISTS sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    session_date DATE NOT NULL,
    session_type VARCHAR(50) NOT NULL, 
    duration_min INT,
    distance_km DECIMAL(5,2),
    intensity TINYINT,               
    sprints_count INT,
    sleep_hours DECIMAL(4,2),
    perceived_fatigue TINYINT,         
    injury_status VARCHAR(20)          
);


CREATE TABLE IF NOT EXISTS matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    match_date DATE NOT NULL,
    opponent VARCHAR(100),
    minutes_played INT,
    goals INT,
    assists INT,
    shots INT,
    tackles INT,
    rating TINYINT,                   
    notes TEXT
);
