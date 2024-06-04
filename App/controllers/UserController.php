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
            $errors['email'] = 'Enter valid email adress';
        }

        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }

        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be between 6 and 50 characters';
        }

        if (!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords must be the same';
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
        }

        //Checking if user with this email already exists
        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            $errors['email'] = "That email is already taken";
            loadView('users/create', ['errors' => $errors]);
            exit;
        }

        //Creating new user
        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'voivodeship' => $voivodeship,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query('INSERT INTO users (name, email, city, voivodeship, password) VALUES (:name, :email, :city, :voivodeship, :password)', $params);

        redirect('/');
    }
}
