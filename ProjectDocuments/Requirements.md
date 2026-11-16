# ksf_FA_HRM_Performance — Requirements

## Purpose
Manage employee performance appraisals: review cycles, ratings, goals, reviewer/employee sign-off and state transitions.

## Business Rules (BR-HRM-04)
- A performance review belongs to one employee (person) for one review period.
- A review period is unique per employee (one review per person per period).
- Rating is on a 1.00–5.00 scale.
- State transitions: draft → submitted → reviewed → acknowledged.
- Only the assigned reviewer may move a review to 'reviewed'; only the employee may acknowledge.
- State changes fire the PERFORMANCE_REVIEW_STATE_CHANGED hook.

## Functional Requirements
- FR-PF-01: Create a performance review for an employee and review period.
- FR-PF-02: List all performance reviews (summary table).
- FR-PF-03: Resolve a single review by id (related-record linking).
- FR-PF-04: Transition a review through its lifecycle with rating/goals/comments.
- FR-PF-05: List reviews for a given employee.
- FR-PF-06: Expose performance review fields via RELATED_TO_FIELDS.

## Non-Functional Requirements
- NF-PF-01: All database access uses native FA db_* calls.
- NF-PF-02: PHP 7.3 compatible runtime.
- NF-PF-03: Unit tests cover DTO round-trip and label resolution.
