<?php

namespace Database\seeders;

class UserSeeder extends Seeder
{
    /**
     * Adds a user to the database
     * 
     * @param string $name username / company name / foundation name
     * @param string $email Must be unique
     * @param string $pass
     * @param int $is_admin
     * @return void
     */
    public function add(string $name, string $email, string $pass, ?int $is_admin = 0): void
    {
        $this->db->exec(
            "INSERT INTO users (naam, email, wachtwoord, date_created, is_admin)
            VALUES ('$name', '$email', '$pass', '" . $this->now() . "', $is_admin)"
        );
    }
}
