<?php

namespace Controllers;

use Database\Database;
use Database\seeders\Seeder;
use PDO;

class MatchController extends Seeder
{
    private Database $database;
    private PDO $conn;

    public function __construct()
    {
        $this->database = new Database();
        $this->conn = $this->database->connect();
    }

    public function post(array $post): void
    {

        $this->conn->query("USE " . $this->database->getDbName());

        $matchIds = [];
        // store a seperate match for each offer that was selected
        foreach ($post['offers'] as $offer) {
            $sql = "INSERT INTO matches (status_id, need_id, offer_id, date_created, date_modified) VALUES (:statusId, :needId, :offerId, :dateCreated, :dateModified)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'statusId' => $post['status'],
                'needId' => $post['need_id'],
                'offerId' => $offer,
                'dateCreated' => $this->now(),
                'dateModified' => $this->now(),
            ]);

            // store the stored matches' ids into array
            $stmt = $this->conn->prepare("SELECT id FROM matches ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $match = $stmt->fetch(PDO::FETCH_ASSOC);
            $matchIds[] = $match['id'];
        }

        // if a log was added, each match should have it's own log
        if (!empty($post['log'])) {
            foreach ($matchIds as $matchId) {
                $sql = "INSERT INTO history_logs (notitie, date_created, admin_id, match_id) VALUES (:log, :dateCreated, :adminId, :matchId)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    'log' => $post['log'],
                    'dateCreated' => $this->now(),
                    'adminId' => $_SESSION['id'],
                    'matchId' => $matchId,
                ]);
            }
        }

        if ($post['need-fulfilled']) {
            $sql = "UPDATE needs SET is_completed = 1 WHERE id = :needId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'needId' => $post['need_id'],
            ]);
        }

        // TODO: If need has been partially fulfilled, subtract the amount of fulfilled product from original amount


        // echo '<pre>';
        // print_r($match);
        // print_r($post);
        // print_r($need);
        // echo '</pre>';

        header('Location: /admin/matches');
        exit();
    }
}
