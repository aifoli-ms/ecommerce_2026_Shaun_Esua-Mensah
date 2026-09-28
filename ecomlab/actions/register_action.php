<?php

// Start the session and load shared helper functions.
require_once __DIR__ . "/../core/core.php";

// Load the controller that handles customer registration.
require_once __DIR__ . "/../controllers/CustomerController.php";

// This file should only process the registration form.
// Send other requests back to the registration page.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("../view/register.php");
}

// Remove extra spaces and HTML tags from an input value.
function cleanInput($value)
{
    return trim(strip_tags($value ?? ""));
}

// Save an error message and return to the registration page.
function failRegistration($message)
{
    $_SESSION["error"] = $message;
    redirect("../view/register.php");
}

// Get and clean the values entered in the registration form.
$name = cleanInput($_POST["customer_name"] ?? "");
$email = cleanInput($_POST["customer_email"] ?? "");
$pass = trim($_POST["customer_pass"] ?? "");
$country = cleanInput($_POST["customer_country"] ?? "");
$city = cleanInput($_POST["customer_city"] ?? "");
$contact = cleanInput($_POST["customer_contact"] ?? "");
$address = cleanInput($_POST["customer_address"] ?? "");

// Make sure all required fields have been completed.
if ($name === "" || $email === "" || $pass === "" || $country === "" || $city === "" || $contact === "" || $address === "") {
    failRegistration("Please fill in all required fields.");
}

// Check that the email has a valid format.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    failRegistration("Please enter a valid email address.");
}

// Check that each value fits within the database field limits.
if (strlen($email) > 50) {
    failRegistration("Email must be 50 characters or less.");
}

if (strlen($name) > 100) {
    failRegistration("Full name must be 100 characters or less.");
}

if (strlen($country) > 100) {
    failRegistration("Country must be 100 characters or less.");
}

if (strlen($city) > 100) {
    failRegistration("City must be 100 characters or less.");
}

if (strlen($contact) > 50) {
    failRegistration("Contact number must be 50 characters or less.");
}

// Check that the contact number contains only allowed characters.
if (!preg_match('/^[0-9+\-\s]{7,15}$/', $contact)) {
    failRegistration("Please enter a valid contact number.");
}

// Require at least eight password characters and one number.
if (strlen($pass) < 8 || !preg_match('/[0-9]/', $pass)) {
    failRegistration("Password must be at least 8 characters and include one digit.");
}



// Ask the controller to register the customer.
$controller = new CustomerController();
$result = $controller->register([
    "name" => $name,
    "email" => $email,
    "password" => $pass,
    "country" => $country,
    "city" => $city,
    "contact" => $contact
]);

// If registration worked, log in the customer and open their account page.
if ($result["success"]) {
    $_SESSION["customer_id"] = $result["customer_id"];
    $_SESSION["user_role"] = 2;
    redirect("../view/account/my_account.php");
}

// Registration failed. Show the controller's error on the form.
failRegistration($result["error"] ?? "Registration failed. Please try again.");
