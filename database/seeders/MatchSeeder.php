<?php

namespace Database\seeders;

class MatchSeeder extends Seeder
{
    /**
     * Adds a Match to the database
     *
     * @param int $needId
     * @param int $offerId
     * @param int|null $statusId
     * @return void
     */
    public function add(int $needId, int $offerId, ?int $statusId = 1): void
    {
        $now = $this->now();

        $this->db->exec(
            "INSERT INTO matches (date_created, date_modified, status_id, need_id, offer_id)
            VALUES ('$now', '$now', $statusId, $needId, $offerId)"
        );
    }
}
