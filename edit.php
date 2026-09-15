<?php

require "db.php";

$id = intval($_GET["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $exercise = trim($_POST["exercise"]);
    $muscle_group = trim($_POST["muscle_group"]);
    $sets = intval($_POST["sets"]);
    $reps = intval($_POST["reps"]);
    $post_id = intval($_POST["id"]);

    $stmt = $conn->prepare(
        "UPDATE workouts SET exercise=?, muscle_group=?, sets=?, reps=? WHERE id=?"
    );

    $stmt->bind_param("ssiii", $exercise, $muscle_group, $sets, $reps, $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM workouts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$workout = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$workout) {
    die("Workout not found.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Workout</title>
</head>

<body>

    <h2>Edit Workout</h2>

    <form method="POST" action="edit.php">

        <input type="hidden" name="id" value="<?php echo $workout['id']; ?>">

        <label>Exercise</label><br>

        <input type="text" name="exercise" value="<?php echo htmlspecialchars($workout['exercise']); ?>" required><br>

        <label>Muscle Group</label><br>

        <input type="text" name="muscle_group" value="<?php echo htmlspecialchars($workout['muscle_group']); ?>" required><br>

        <label>Sets</label><br>

        <input type="number" name="sets" value="<?php echo $workout['sets']; ?>" required><br>

        <label>Reps</label><br>

        <input type="number" name="reps" value="<?php echo $workout['reps']; ?>" required><br>

        <button type="submit">Update Workout</button>

    </form>

    <a href="index.php">Back</a>

</body>

</html>
