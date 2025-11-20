<?php

namespace Database\seeders;

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
        $this->db->exec(
            "INSERT INTO offers (model, staat, hoeveelheid, beschrijving, postcode, type_id, user_id)
            VALUES ('$model', '$staat', $hoeveelheid, '$beschrijving', '$postcode', $typeId, $userId)"
        );
    }
}
