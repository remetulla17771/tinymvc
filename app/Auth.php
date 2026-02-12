<?php

namespace app;

interface Auth
{
    public static function findIdentity($id);

    public static function findByUsername(string $username);

    public function validatePassword(string $password): bool;

    public function getId();
}
