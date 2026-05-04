<?php

namespace Database\seeders;

class HistoryLogsSeeder extends Seeder
{
    /**
     * Adds a History_log to the database
     *
     * @param string $notitie
     * @param int $adminId
     * @param int $matchId
     * @return void
     */
    public function add(string $notitie, int $adminId, int $matchId): void
    {
        $this->db->exec(
            "INSERT INTO history_logs (notitie, date_created, admin_id, match_id)
            VALUES ('$notitie', '" . $this->now() . "', $adminId, $matchId)"
        );
    }
}
