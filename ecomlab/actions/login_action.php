<?php

// Start the session and load shared helper functions.
require_once __DIR__ . "/../core/core.php";

// This file should only process the login form.
// Send other requests back to the login page.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/login.php"));
}

// Load the controller that handles customer login.
require_once __DIR__ . "/../controllers/CustomerController.php";

// Get and clean the email and password entered in the form.
$email = filter_var(trim($_POST["customer_email"] ?? ""), FILTER_SANITIZE_EMAIL);
$pass = trim($_POST["customer_pass"] ?? "");

// Make sure both fields are filled and the email is valid.
if ($email === "" || $pass === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["error"] = "Please enter a valid email address and password.";
    redirect(app_url("views/login.php"));
}

// Ask the controller to check the login details.
$controller = new CustomerController();
$result = $controller->login($email, $pass);

// A customer ID means the login was successful.
if (isset($result["customer_id"])) {
    // Create a new session ID for better security after login.
    session_regenerate_id(true);

    // Save the logged-in customer's details in the session.
    $_SESSION["customer_id"] = $result["customer_id"];
    $_SESSION["customer_name"] = $result["customer_name"];
    $_SESSION["customer_email"] = $result["customer_email"];
    $_SESSION["user_role"] = $result["user_role"];

    // Take the customer to the home page.
    redirect(app_url("index.php"));
}

// Login failed. Save the error and return to the login page.
$_SESSION["error"] = $result["error"] ?? "Invalid email address or password.";
redirect(app_url("views/login.php"));
