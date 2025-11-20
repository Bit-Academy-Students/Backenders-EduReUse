<?php

namespace Database\seeders;

use Exception;

class OfferSeeder extends Seeder
{
    /**
     * Adds an offer to the database
     * 
     * @param string $model Short summary/title of product
     * @param string $staat 'nieuw' / 'gebruikt' / 'beschadigd' / 'niet-werkend'
     * @param int $hoeveelheid Product amount
     * @param string $beschrijving Detailed description
     * @param string $postcode
     * @param int $typeId
     * @param int $userId
     * @return void
     */
    public function addOffer(
        string $model,
        string $staat,
        int $hoeveelheid,
        string $beschrijving,
        string $postcode,
        int $typeId,
        int $userId
    ): void {
        $pattern = '/(?<letters>\d{4}) (?<nummers>[a-zA-Z]{2})/';
        if (!preg_match($pattern, $postcode)) {
            throw new Exception("Verkeerde postcode '$postcode' ingevoerd, houdt het format '1234 AB' aan." . PHP_EOL);
        }

        $this->db->exec(
            "INSERT INTO offers (model, staat, hoeveelheid, beschrijving, postcode, type_id, user_id)
            VALUES ('$model', '$staat', $hoeveelheid, '$beschrijving', '$postcode', $typeId, $userId)"
        );
    }
}
