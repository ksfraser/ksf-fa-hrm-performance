<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Performance\Service;
use Ksfraser\FrontAccounting\HRM\Performance\DTO\PerformanceDTO;
use Ksfraser\FrontAccounting\HRM\Performance\Repository\PerformanceRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class PerformanceService {
    private $repo;
    public function __construct(?PerformanceRepository $repo = null) { $this->repo = $repo ?? new PerformanceRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('hrm_performance_review', '0', 'hrm_performance_review', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Performance Review #') . (string) $id);
        }
        $r = PerformanceDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('hrm_performance_review', (string) $r->review_id, 'hrm_performance_review', (string) $r->review_id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'review_id' => (string) $r->review_id,
                'person_id' => $r->person_name,
                'reviewer_id' => $r->reviewer_name,
                'review_period' => $r->review_period,
                'rating' => $r->rating,
                'state' => $r->state,
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_HRM_Performance/pages/performance_review_form.php?action=edit&review_id=' . $r->review_id,
            'label' => $r->getDisplayLabel(),
        );
    }
    public function listForPerson(int $personId): array {
        $rows = $this->repo->listForPerson($personId);
        $results = array();
        foreach ($rows as $row) {
            $r = PerformanceDTO::fromArray($row);
            $results[] = array(
                'id' => $r->review_id,
                'dto' => new RelatedRecordDto('hrm_performance_review', (string) $r->review_id, 'hrm_performance_review', (string) $r->review_id, null),
                'fields' => array(
                    'review_id' => (string) $r->review_id,
                    'person_id' => $r->person_name,
                    'reviewer_id' => $r->reviewer_name,
                    'review_period' => $r->review_period,
                    'rating' => $r->rating,
                    'state' => $r->state,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRM_Performance/pages/performance_review_form.php?action=edit&review_id=' . $r->review_id,
                'label' => $r->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $r = PerformanceDTO::fromArray($row);
            $results[] = array(
                'id' => $r->review_id,
                'fields' => array(
                    'review_id' => (string) $r->review_id,
                    'person_name' => $r->person_name,
                    'reviewer_name' => $r->reviewer_name,
                    'review_period' => $r->review_period,
                    'rating' => $r->rating,
                    'state' => $r->state,
                    'url' => '/modules/ksf_FA_HRM_Performance/pages/performance_review_form.php?action=edit&review_id=' . $r->review_id,
                ),
            );
        }
        return $results;
    }
    public function insertReview(array $data): int {
        return $this->repo->insertReview($data);
    }
    public function updateState(int $reviewId, string $state, string $comments = null): void {
        $this->repo->updateState($reviewId, $state, $comments);
        performance_review_state_changed((string) $reviewId, '', $state, array('comments' => $comments));
    }
    public function fieldMap(): array { return $this->labelMap(); }
    public function labelMap(): array {
        return array(
            'review_id' => _('Review ID'),
            'person_id' => _('Employee'),
            'reviewer_id' => _('Reviewer'),
            'review_period' => _('Review Period'),
            'rating' => _('Rating'),
            'state' => _('Status'),
        );
    }
    public function getStateTransitions(string $currentState): array {
        $transitions = array(
            'draft' => array('submitted' => _('Submit')),
            'submitted' => array('reviewed' => _('Complete Review')),
            'reviewed' => array('acknowledged' => _('Acknowledge')),
        );
        return isset($transitions[$currentState]) ? $transitions[$currentState] : array();
    }
    public function isAllowedTransition(string $fromState, string $toState): bool {
        $transitions = $this->getStateTransitions($fromState);
        return isset($transitions[$toState]);
    }
}
