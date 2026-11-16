<?php
declare(strict_types=1);

if (!function_exists('hook_invoke_all')) {
    return;
}

function performance_review_state_changed($reviewId, $fromState, $toState, $meta = array()) {
    hook_invoke_all('PERFORMANCE_REVIEW_STATE_CHANGED', array(
        'review_id' => $reviewId,
        'from_state' => $fromState,
        'to_state' => $toState,
        'meta' => $meta,
    ));
}
