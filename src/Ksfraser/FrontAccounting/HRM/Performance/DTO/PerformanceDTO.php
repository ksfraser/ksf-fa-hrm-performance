<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Performance\DTO;
final class PerformanceDTO {
    public $review_id;
    public $person_id;
    public $person_name;
    public $reviewer_id;
    public $reviewer_name;
    public $review_period;
    public $rating;
    public $goals;
    public $comments;
    public $state;
    public $created_at;
    public $updated_at;
    public function __construct(
        int $review_id,
        int $person_id = 0,
        string $person_name = '',
        int $reviewer_id = 0,
        string $reviewer_name = '',
        string $review_period = '',
        string $rating = '0.00',
        string $goals = '',
        string $comments = '',
        string $state = 'draft',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->review_id = $review_id;
        $this->person_id = $person_id;
        $this->person_name = $person_name;
        $this->reviewer_id = $reviewer_id;
        $this->reviewer_name = $reviewer_name;
        $this->review_period = $review_period;
        $this->rating = $rating;
        $this->goals = $goals;
        $this->comments = $comments;
        $this->state = $state;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function getDisplayLabel(): string {
        if ($this->person_name !== '') {
            return sprintf(_('%s review — %s'), $this->review_period, $this->person_name);
        }
        return _('Performance Review #') . (string) $this->review_id;
    }
    public function toArray(): array {
        return array(
            'review_id' => $this->review_id,
            'person_id' => $this->person_id,
            'person_name' => $this->person_name,
            'reviewer_id' => $this->reviewer_id,
            'reviewer_name' => $this->reviewer_name,
            'review_period' => $this->review_period,
            'rating' => $this->rating,
            'goals' => $this->goals,
            'comments' => $this->comments,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['review_id'] ?? 0),
            (int) ($data['person_id'] ?? 0),
            (string) ($data['person_name'] ?? ''),
            (int) ($data['reviewer_id'] ?? 0),
            (string) ($data['reviewer_name'] ?? ''),
            (string) ($data['review_period'] ?? ''),
            (string) ($data['rating'] ?? '0.00'),
            (string) ($data['goals'] ?? ''),
            (string) ($data['comments'] ?? ''),
            (string) ($data['state'] ?? 'draft'),
            (string) ($data['created_at'] ?? ''),
            (string) ($data['updated_at'] ?? '')
        );
    }
}
