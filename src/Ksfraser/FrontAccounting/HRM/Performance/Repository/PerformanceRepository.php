<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Performance\Repository;
final class PerformanceRepository {
    private string $table = 'hrm_performance_reviews';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT r.*, p.name AS person_name, rv.name AS reviewer_name FROM " . TB_PREF . $this->table . " r"
            . " LEFT JOIN " . TB_PREF . "crm_persons p ON r.person_id = p.id"
            . " LEFT JOIN " . TB_PREF . "crm_persons rv ON r.reviewer_id = rv.id"
            . " ORDER BY r.created_at DESC, r.review_id DESC";
        $result = db_query($sql, 'Could not load performance reviews');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT r.*, p.name AS person_name, rv.name AS reviewer_name FROM " . TB_PREF . $this->table . " r"
            . " LEFT JOIN " . TB_PREF . "crm_persons p ON r.person_id = p.id"
            . " LEFT JOIN " . TB_PREF . "crm_persons rv ON r.reviewer_id = rv.id"
            . " WHERE r.review_id = " . (int) $id;
        $result = db_query($sql, 'Could not load performance review');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function listForPerson(int $personId): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT r.*, p.name AS person_name, rv.name AS reviewer_name FROM " . TB_PREF . $this->table . " r"
            . " LEFT JOIN " . TB_PREF . "crm_persons p ON r.person_id = p.id"
            . " LEFT JOIN " . TB_PREF . "crm_persons rv ON r.reviewer_id = rv.id"
            . " WHERE r.person_id = " . (int) $personId
            . " ORDER BY r.review_period DESC";
        $result = db_query($sql, 'Could not load performance reviews by person');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function insertReview(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                person_id, reviewer_id, review_period, rating, goals, comments, state, created_at, updated_at)
            VALUES ("
            . $this->intVal($data['person_id'] ?? 0) . ", "
            . $this->intVal($data['reviewer_id'] ?? 0) . ", "
            . $this->escape($data['review_period'] ?? '') . ", "
            . $this->floatVal($data['rating'] ?? 0) . ", "
            . $this->escape($data['goals'] ?? '') . ", "
            . $this->escape($data['comments'] ?? '') . ", "
            . $this->escape($data['state'] ?? 'draft') . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ")";
        $this->dbQuery($sql);
        return $this->dbInsertId();
    }
    public function updateState(int $reviewId, string $state, string $comments = null): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = ["state = " . $this->escape($state)];
        $sets[] = "comments = " . $this->escape($comments ?? '');
        $sets[] = "updated_at = " . $this->escape(date('Y-m-d H:i:s'));
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets)
            . " WHERE review_id = " . $this->intVal($reviewId);
        $this->dbQuery($sql);
    }
    private function dbQuery($sql, $errorMsg = null) {
        return db_query($sql, $errorMsg);
    }
    private function dbInsertId() {
        return db_insert_id();
    }
    private function escape($value): string {
        if ($value === null) { return 'NULL'; }
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return (int) $value;
    }
    private function floatVal($value): float {
        return (float) $value;
    }
}
