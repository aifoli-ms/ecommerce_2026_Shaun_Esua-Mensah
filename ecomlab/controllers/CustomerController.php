<?php

require_once __DIR__ . "/../classes/CustomerClass.php";

// Connects customer actions to the customer model.
class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new CustomerClass();
    }

    // Register a customer unless the email is already in use.
    public function register($data)
    {
        if ($this->customer->emailExists($data["email"])) {
            return [
                "success" => false,
                "error" => "Email already registered."
            ];
        }

        $customerId = $this->customer->addCustomer(
            $data["name"],
            $data["email"],
            $data["password"],
            $data["country"],
            $data["city"],
            $data["contact"]
        );

        if (!$customerId) {
            return [
                "success" => false,
                "error" => "Registration failed. Please try again."
            ];
        }

        return [
            "success" => true,
            "customer_id" => $customerId
        ];
    }

    // Return the customer when the login details are correct.
    public function login($email, $pass)
    {
        $customer = $this->customer->login($email, $pass);

        if ($customer) {
            return $customer;
        }

        return [
            "success" => false,
            "error" => "Invalid email address or password."
        ];
    }

}
