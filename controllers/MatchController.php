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
        $this->conn->query("USE " . $this->database->getDbName());
    }

    public function post(array $post): void
    {
        if (!isset($_SESSION['id'])) {
            header('location: /login');
            exit();
        }

        if (!isset($post['offers']) || !isset($post['status']) || !isset($post['need_id'])) {
            $_SESSION['error'] = 'Er is iets fout gegaan...';
            header('location: ' . $post['previous-url']);
            exit();
        }

        $matchIds = [];
        $offers = [];
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

            // add the stored matches' ids into array
            $stmt = $this->conn->prepare("SELECT id FROM matches ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $match = $stmt->fetch(PDO::FETCH_ASSOC);
            $matchIds[] = $match['id'];
            $sql = "SELECT * FROM offers WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $offer]);
            $offers[] = $stmt->fetch(PDO::FETCH_ASSOC);

            // set offers' 'is_completed' status to true in database
            $sql = "UPDATE offers SET is_completed = 1, date_modified = :now WHERE id = :offerId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'now' => $this->now(),
                'offerId' => $offer,
            ]);
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

        // if a need has been fulfilled, set to 'completed' in database        
        if ($post['need-fulfilled']) {
            $sql = "UPDATE needs SET is_completed = 1, date_modified = :now WHERE id = :needId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'now' => $this->now(),
                'needId' => $post['need_id'],
            ]);
        }

        // if need has been partially fulfilled, subtract the amount of fulfilled product from original amount
        if (!$post['need-fulfilled']) {
            $sql = "SELECT * FROM needs WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $post['need_id']]);
            $need = $stmt->fetch(PDO::FETCH_ASSOC);

            // count offers' amount and determine remainder
            $count = 0;
            foreach ($offers as $offer) {
                $count += $offer['hoeveelheid'];
            }
            $remainder = (intval($need['hoeveelheid']) - $count);

            // update amount in database
            $sql = "UPDATE needs SET hoeveelheid = :remainder, date_modified = :now WHERE id = :needId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'remainder' => $remainder,
                'now' => $this->now(),
                'needId' => $post['need_id'],
            ]);
        }

        // echo '<pre>';
        // print_r($match);
        // print_r($post);
        // print_r($offers);
        // print_r($need);
        // echo '</pre>';

        header('Location: /admin/matches');
        exit();
    }
}
