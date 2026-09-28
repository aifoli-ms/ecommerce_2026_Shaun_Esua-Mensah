<?php


ob_start();

// Start a session if one is not already running.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("Africa/Accra");

require_once __DIR__ . "/db_class.php";

// Redirect to another page and stop the script.
function redirect($url)
{
    header("Location: " . $url);
    exit;
}

// Build a URL from the project's root folder.
function app_url($path = "")
{
    $projectRoot = realpath(__DIR__ . "/..");
    $scriptFile = realpath($_SERVER["SCRIPT_FILENAME"] ?? "");
    $scriptUrl = $_SERVER["SCRIPT_NAME"] ?? "";
    $basePath = "";

    // Keep URL prefixes such as /~username when the server uses user folders.
    if ($projectRoot && $scriptFile && $scriptUrl && strpos($scriptFile, $projectRoot) === 0) {
        $relativeScript = str_replace(DIRECTORY_SEPARATOR, "/", substr($scriptFile, strlen($projectRoot)));

        if ($relativeScript !== "" && substr($scriptUrl, -strlen($relativeScript)) === $relativeScript) {
            $basePath = substr($scriptUrl, 0, -strlen($relativeScript));
        }
    }

    // Use the document root as a fallback on standard server setups.
    if ($basePath === "") {
        $documentRoot = realpath($_SERVER["DOCUMENT_ROOT"] ?? "");

        if ($documentRoot && $projectRoot && strpos($projectRoot, $documentRoot) === 0) {
            $basePath = str_replace(DIRECTORY_SEPARATOR, "/", substr($projectRoot, strlen($documentRoot)));
        }
    }

    return rtrim($basePath, "/") . "/" . ltrim($path, "/");
}

// Check whether a customer is logged in.
function is_logged_in()
{
    return isset($_SESSION["customer_id"]);
}

// Check whether the logged-in customer has admin role 1.
function is_admin()
{
    if (!is_logged_in() || !isset($_SESSION["user_role"])) {
        return false;
    }

    return $_SESSION["user_role"] === 1 || $_SESSION["user_role"] === "1";
}

// Send guests to the login page.
function require_login()
{
    if (!is_logged_in()) {
        redirect(app_url("views/login.php"));
    }
}

// Keep non-admin users away from admin pages.
function require_admin()
{
    if (!is_admin()) {
        $_SESSION["error"] = "Administrator access is required.";
        redirect(app_url("index.php"));
    }
}

