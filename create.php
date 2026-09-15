<?php

require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $exercise = trim($_POST["exercise"]);
    $muscle_group = trim($_POST["muscle_group"]);
    $sets = intval($_POST["sets"]);
    $reps = intval($_POST["reps"]);

    $stmt = $conn->prepare(
        "INSERT INTO workouts (exercise, muscle_group, sets, reps) VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssii", $exercise, $muscle_group, $sets, $reps);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Workout</title>
</head>

<body>

    <h2>Add Workout</h2>

    <form method="POST" action="create.php">

        <label>Exercise</label><br>
        <input type="text" name="exercise" required><br>

        <label>Muscle Group</label><br>
        <input type="text" name="muscle_group" required><br>

        <label>Sets</label><br>
        <input type="number" name="sets" required><br>

        <label>Reps</label><br>
        <input type="number" name="reps" required><br>

        <button type="submit">Save Workout</button>

    </form>

    <a href="index.php">Back</a>

</body>

</html>