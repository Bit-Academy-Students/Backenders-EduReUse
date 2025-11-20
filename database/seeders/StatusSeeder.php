<?php

namespace Database\seeders;

class StatusSeeder extends Seeder
{
    /**
     * Adds a Status to the database
     * 
     * @param string $label
     * @return void
     */
    public function addStatus(string $label): void
    {
        $this->db->exec(
            "INSERT INTO statuses (label)
            VALUES ('$label')"
        );
    }
}
