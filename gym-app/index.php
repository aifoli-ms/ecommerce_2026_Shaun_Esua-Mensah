<?php

require "db.php";

$result = $conn->query("SELECT * FROM workouts ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Workouts</title>
</head>

<body>

    <h1>My Workouts</h1>

    <a href="create.php">+ Add Workout</a>

    <?php while ($row = $result->fetch_assoc()): ?>

        <div>
            <h3><?php echo htmlspecialchars($row['exercise']); ?></h3>

            <p>Muscle Group: <?php echo htmlspecialchars($row['muscle_group']); ?></p>

            <p>Sets: <?php echo $row['sets']; ?></p>

            <p>Reps: <?php echo $row['reps']; ?></p>

            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>

            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this workout?');">
                Delete
            </a>
        </div>

    <?php endwhile; ?>

</body>

</html>

<?php $conn->close(); ?>