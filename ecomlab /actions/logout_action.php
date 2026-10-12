<?php

// Start the session and load shared helper functions.
require_once __DIR__ . "/../core/core.php";

// Remove all logged-in customer details from the session.
unset(
    $_SESSION["customer_id"],
    $_SESSION["customer_name"],
    $_SESSION["customer_email"],
    $_SESSION["user_role"]
);

// Create a new session ID for security.
session_regenerate_id(true);

// Send the customer back to the login page.
redirect(app_url("views/login.php"));
