# ksf_FA_HRM_Performance — Requirements Traceability Matrix

| Req ID | Requirement | Source | Implemented In | Verified By |
|--------|-------------|--------|----------------|-------------|
| BR-HRM-04 | Performance appraisals | HR-CRM_gaps.md H4 | PerformanceService | unit tests |
| FR-PF-01 | Create review | BR-HRM-04 | PerformanceRepository::insertReview | PerformanceDtoTest |
| FR-PF-02 | List reviews | BR-HRM-04 | PerformanceService::listAll | (tab controller) |
| FR-PF-03 | Resolve review by id | BR-HRM-04 | PerformanceService::resolveById | (capability hook) |
| FR-PF-04 | Transition lifecycle | BR-HRM-04 | PerformanceService::updateState | (state transitions) |
| FR-PF-05 | List for employee | BR-HRM-04 | PerformanceRepository::listForPerson | (service) |
| FR-PF-06 | RELATED_TO_FIELDS | BR-HRM-04 | hooks.php RELATED_TO_FIELDS | (capability) |
| NF-PF-01 | Native db_* calls | AGENTS_ARCH | repository | code review |
| NF-PF-02 | PHP 7.3 compatible | AGENTS_ARCH | all src | code review |
| NF-PF-03 | Unit tests | AGENTS_ARCH | tests/Unit | phpunit |
