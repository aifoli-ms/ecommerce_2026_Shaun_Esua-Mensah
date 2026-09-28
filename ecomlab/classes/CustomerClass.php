<?php

require_once __DIR__ . "/../core/db_class.php";

// Handles all customer database queries.
class CustomerClass extends Database
{
    // Check whether an email is already registered.
    public function emailExists($email)
    {
        $sql = "SELECT customer_email FROM customer WHERE customer_email = ? LIMIT 1";
        $row = $this->fetchOne($sql, [$email]);

        return $row ? true : false;
    }

    // Find one customer using their email address.
    public function getCustomerByEmail($email)
    {
        $sql = "SELECT * FROM customer WHERE customer_email = ?";

        return $this->fetchOne($sql, [$email]);
    }

    // Check the submitted password against the saved hash.
    public function login($email, $pass)
    {
        $customer = $this->getCustomerByEmail($email);

        if ($customer && password_verify($pass, $customer["customer_pass"])) {
            return $customer;
        }

        return false;
    }

    // Save a new customer with a secure password hash.
    public function addCustomer($name, $email, $pass, $country, $city, $contact)
    {
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

        $sql = "
            INSERT INTO customer (
                customer_name,
                customer_email,
                customer_pass,
                customer_country,
                customer_city,
                customer_contact,
                customer_image,
                user_role
            ) VALUES (?, ?, ?, ?, ?, ?, NULL, 2)
        ";

        if ($this->execute($sql, [$name, $email, $hashedPass, $country, $city, $contact])) {
            return $this->lastInsertId();
        }

        return false;
    }

}
