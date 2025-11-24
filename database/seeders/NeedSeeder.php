<?php

namespace Database\seeders;

use Exception;

class NeedSeeder extends Seeder
{
    /**
     * Adds a Need to the database
     * 
     * @param string $model Short summary/title of product
     * @param int $hoeveelheid Product amount needed
     * @param string $postcode
     * @param string $deadline
     * @param int $typeId
     * @param int $userId
     * @return void
     */
    public function add(
        string $model,
        int $hoeveelheid,
        string $postcode,
        string $deadline,
        int $typeId,
        int $userId,
    ): void {
        $pattern = '/(?<letters>\d{4}) (?<nummers>[a-zA-Z]{2})/';
        if (!preg_match($pattern, $postcode)) {
            throw new Exception("Verkeerde postcode '$postcode' ingevoerd, houdt het format '1234 AB' aan." . PHP_EOL);
        }

        $pattern = '/^(?<year>\d{4})-(?<month>\d{2})-(?<days>\d{2})$/';
        if (!preg_match($pattern, $deadline)) {
            throw new Exception("Verkeerde deadline '$deadline' ingevoerd, houdt het format 'YYYY-MM-DD' aan." . PHP_EOL);
        }

        $this->db->exec(
            "INSERT INTO needs (model, hoeveelheid, postcode, deadline, type_id, user_id)
            VALUES ('$model', $hoeveelheid, '$postcode', '$deadline', $typeId, $userId)"
        );
    }
}
