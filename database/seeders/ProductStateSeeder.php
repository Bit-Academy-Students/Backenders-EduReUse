<?php

namespace Database\seeders;

class ProductStateSeeder extends Seeder
{
    /**
     * Adds a Status to the database
     * 
     * @param string $label
     * @return void
     */
    public function addProductState(string $label): void
    {
        $this->db->exec("INSERT INTO product_states (label) VALUES ('$label')");
    }
}
