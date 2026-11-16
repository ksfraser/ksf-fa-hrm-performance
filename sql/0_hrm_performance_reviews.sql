-- ksf_FA_HRM_Performance: performance review tables

CREATE TABLE IF NOT EXISTS `0_hrm_performance_reviews` (
  `review_id`     INT(11) NOT NULL AUTO_INCREMENT,
  `person_id`     INT(11) NOT NULL COMMENT 'FK to crm_persons.id (employee)',
  `reviewer_id`   INT(11) NOT NULL COMMENT 'FK to crm_persons.id (reviewer)',
  `review_period` VARCHAR(32) NOT NULL COMMENT 'e.g. 2026-H1',
  `rating`        DECIMAL(3,2) NOT NULL DEFAULT 0.00 COMMENT '1.00 to 5.00',
  `goals`         TEXT DEFAULT NULL,
  `comments`      TEXT DEFAULT NULL,
  `state`         VARCHAR(24) NOT NULL DEFAULT 'draft' COMMENT 'draft|submitted|reviewed|acknowledged',
  `created_at`    DATETIME NOT NULL,
  `updated_at`    DATETIME NOT NULL,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `uq_person_period` (`person_id`, `review_period`),
  KEY `idx_person` (`person_id`),
  KEY `idx_reviewer` (`reviewer_id`),
  KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
