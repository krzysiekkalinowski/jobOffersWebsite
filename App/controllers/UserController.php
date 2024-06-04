<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the lagin page
     * 
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Show the form to regiter a new user
     * 
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * Store user to db
     * 
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $voivodeship = $_POST['voivodeship'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        $errors = [];

        if (!Validation::email($email)) {
            $errors['email'] = 'Insert valid email adress';
        }

        if (!empty($errors)) {
            loadView(
                'users/create',
                [
                    'errors' => $errors,
                    'user' => [
                        'name' => $name,
                        'email' => $email,
                        'city' => $city,
                        'voivodeship' => $voivodeship
                    ]
                ]
            );
            exit;
        } else {
            inspectAndDie($errors);
        }
    }
}
