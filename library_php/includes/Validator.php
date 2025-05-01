<?php
class Validator {
    private $errors = [];

    public function validateEmail($email) {
        if (empty($email)) {
            return "Email is required";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        return null;
    }

    public function validatePassword($password, $minLength = 6) {
        if (empty($password)) {
            return "Password is required";
        }
        if (strlen($password) < $minLength) {
            return "Password must be at least {$minLength} characters long";
        }
        return null;
    }

    public function validateLogin($email, $password) {
        $errors = [];
        $emailError = $this->validateEmail($email);
        if ($emailError) {
            $errors['email'] = $emailError;
        }
        $passwordError = $this->validatePassword($password);
        if ($passwordError) {
            $errors['password'] = $passwordError;
        }
        return $errors;
    }

    public function validateRegistration($username, $email, $password, $confirmPassword) {
        $errors = [];

        if (empty($username)) {
            $errors['username'] = "Username is required";
        } elseif (strlen($username) < 3) {
            $errors['username'] = "Username must be at least 3 characters long";
        }

        $emailError = $this->validateEmail($email);
        if ($emailError) {
            $errors['email'] = $emailError;
        }

        $passwordError = $this->validatePassword($password);
        if ($passwordError) {
            $errors['password'] = $passwordError;
        }

        if ($password !== $confirmPassword) {
            $errors['confirm'] = "Passwords do not match";
        }

        return $errors;
    }
}
?> 