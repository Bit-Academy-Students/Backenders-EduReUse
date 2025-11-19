<?php

namespace Database\seeders;

class TypeSeeder extends Seeder
{
    /**
     * Adds a type to the database
     * 
     * @param string $type
     * @return void
     */
    public function addType(string $type): void
    {
        $this->db->exec(
            "INSERT INTO types (type)
            VALUES ('$type')"
        );
    }
}
