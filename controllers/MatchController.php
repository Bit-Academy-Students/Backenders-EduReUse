<?php

namespace Controllers;

use Database\Database;
use Database\seeders\Seeder;
use Exception;
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

    /**
     * Stores a match into the database
     * 
     * stores seperate matches for each offer that was selected
     * 
     * @param array $post
     * @return void
     */
    public function post(array $post): void
    {
        // redirect user to login if not logged in
        if (!isset($_SESSION['id'])) {
            header('location: /login');
            exit();
        }

        // error handling
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
                'statusId' => htmlspecialchars($post['status']),
                'needId' => htmlspecialchars($post['need_id']),
                'offerId' => htmlspecialchars($offer),
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

            if ($post['status'] == 5 || $post['status'] == 6 || $post['status'] == 7) {
                // update date_delivered, date_modified, etc. into DB whenever status gets set to 'delivered', 'refurbished', etc.
                $queryData = $this->getUpdateRowQuery($post['status']);

                // update into db per match
                foreach ($matchIds as $matchId) {
                    $sql = $queryData[0];
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([
                        $queryData[1] => $this->now(),
                        'dateModified' => $this->now(),
                        'matchId' => $matchId
                    ]);
                }
            }
        }

        // if a log was added, each match should have it's own log
        if (!empty($post['log'])) {
            foreach ($matchIds as $matchId) {
                $sql = "INSERT INTO history_logs (notitie, date_created, admin_id, match_id) VALUES (:log, :dateCreated, :adminId, :matchId)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    'log' => htmlspecialchars($post['log']),
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
                'needId' => htmlspecialchars($post['need_id']),
            ]);
        }

        // if need has been partially fulfilled, subtract the amount of fulfilled product from original amount
        if (!$post['need-fulfilled']) {
            $sql = "SELECT * FROM needs WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => htmlspecialchars($post['need_id'])]);
            $need = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$need) {
                throw new Exception('De opgegeven aanvraag bestaat niet.');
            }

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
                'needId' => htmlspecialchars($post['need_id']),
            ]);
        }

        header('Location: /admin/matches');
        exit();
    }

    /**
     * Adds log to database
     * 
     * @param array $post $_POST variables
     * @return void
     * @throws Exception
     */
    public function addLog(array $post): void
    {
        // redirect user to login if not logged in
        if (!isset($_SESSION['id'])) {
            header('location: /login');
            exit();
        }

        // error handling
        $newStatusId = (!empty($post['status']) ? htmlspecialchars($post['status']) : throw new Exception("Geen status meegegeven"));
        $originalStatusId = (!empty($post['original-match-status']) ? htmlspecialchars($post['original-match-status']) : throw new Exception("Oude status ontbreekt"));
        $log = (!empty($post['new-log'])) ? htmlspecialchars(trim($post['new-log'])) : throw new Exception("Geen log meegegeven");
        $matchId = (!empty($post['match-id'])) ? htmlspecialchars($post['match-id']) : throw new Exception("Geen match meegegeven");

        // if different status was selected -> update to database
        if ($originalStatusId !== $newStatusId) {
            $sql = "UPDATE matches SET status_id = :statusId, date_modified = :dateModified WHERE id = :matchId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'statusId' => $newStatusId,
                'dateModified' => $this->now(),
                'matchId' => $matchId,
            ]);

            // update date_delivered, date_modified, etc. into DB whenever status gets set to 'delivered', 'refurbished', etc.
            $queryData = $this->getUpdateRowQuery($newStatusId);

            // update into db
            $sql = $queryData[0];
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $queryData[1] => $this->now(),
                'dateModified' => $this->now(),
                'matchId' => $matchId
            ]);
        }

        // insert into db
        $sql = "INSERT INTO history_logs (notitie, date_created, admin_id, match_id)
        VALUES (:log, :dateCreated, :adminId, :matchId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'log' => $log,
            'dateCreated' => $this->now(),
            'adminId' => $_SESSION['id'],
            'matchId' => $matchId,
        ]);

        // redirect to detailpage
        header("location: /admin/matches/$matchId");
        exit();
    }

    /**
     * Returns query and tablerow name in an array for Matches updates
     * 
     * @param int $newStatusId
     * @return array contains SQL query and tablerow name
     */
    private function getUpdateRowQuery(int $newStatusId): array
    {
        // status id's and matching data
        $returns = [];
        $arr = [
            5 => ['date_pickup' => 'datePickup'],
            6 => ['date_refurbished' => 'dateRefurbished'],
            7 => ['date_delivered' => 'dateDelivered'],
        ];

        // loop over array
        foreach ($arr as $statusId => $columns) {
            if ($newStatusId == $statusId) {
                foreach ($columns as $col => $row) {
                    $returns[0] = "UPDATE matches
                            SET $col = :$row, date_modified = :dateModified
                            WHERE id = :matchId";
                    $returns[1] = $row;
                }
            }
        }

        return $returns;
    }
}
