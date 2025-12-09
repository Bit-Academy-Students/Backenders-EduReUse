<?php

namespace Database\seeders;

use Exception;

class OfferSeeder extends Seeder
{
    /**
     * Adds an offer to the database
     * 
     * @param string $title Short summary/title of product
     * @param int $staat 'nieuw' / 'gebruikt' / 'beschadigd' / 'niet-werkend'
     * @param int $hoeveelheid Product amount
     * @param string $beschrijving Detailed description
     * @param string $productUrl Link to official product
     * @param string $postcode
     * @param int $typeId
     * @param int $userId
     * @return void
     */
    public function add(
        string $title,
        int $staatId,
        int $hoeveelheid,
        string $beschrijving,
        string $postcode,
        int $typeId,
        int $userId,
        string $productUrl,
        ?int $isCompleted = 0,
    ): void {
        $pattern = '/(?<letters>\d{4}) (?<nummers>[a-zA-Z]{2})/';
        if (!preg_match($pattern, $postcode)) {
            throw new Exception("Verkeerde postcode '$postcode' ingevoerd, houdt het format '1234 AB' aan." . PHP_EOL);
        }

        $now = $this->now();
        $this->db->exec(
            "INSERT INTO offers (titel, staat_id, hoeveelheid, beschrijving, postcode, date_created, date_modified, type_id, user_id, product_url, is_completed)
            VALUES ('$title', $staatId, $hoeveelheid, '$beschrijving', '$postcode', '$now', '$now', $typeId, $userId, '$productUrl', $isCompleted)"
        );
    }
}
