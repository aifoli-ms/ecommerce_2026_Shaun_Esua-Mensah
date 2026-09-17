<?php

require "db.php";

$sql = "CREATE TABLE IF NOT EXISTS workouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exercise VARCHAR(255) NOT NULL,
    muscle_group VARCHAR(255) NOT NULL,
    sets INT NOT NULL,
    reps INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Setup complete!</h2>";
    echo "<p><a href='index.php'>Go to Workout App</a></p>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();