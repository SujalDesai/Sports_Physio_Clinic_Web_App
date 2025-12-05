CREATE DATABASE IF NOT EXISTS football_analytics;
USE football_analytics;

-- Training sessions table
CREATE TABLE IF NOT EXISTS sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    session_date DATE NOT NULL,
    session_type VARCHAR(50) NOT NULL, -- match, sprint, stamina, gym, recovery
    duration_min INT,
    distance_km DECIMAL(5,2),
    intensity TINYINT,                 -- 1–10
    sprints_count INT,
    sleep_hours DECIMAL(4,2),
    perceived_fatigue TINYINT,         -- 1–10
    injury_status VARCHAR(20)          -- OK, minor, injured
);

-- Match performance table
CREATE TABLE IF NOT EXISTS matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    match_date DATE NOT NULL,
    opponent VARCHAR(100),
    minutes_played INT,
    goals INT,
    assists INT,
    shots INT,
    tackles INT,
    rating TINYINT,                    -- 1–10 self-rating
    notes TEXT
);
