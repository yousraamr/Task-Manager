<?php

interface UserInterface {
    public function findByEmail($email);
    public function create($fullname, $email, $password, $role);
    public function updatePassword($userId, $newPassword);
    public function findById($id);
}