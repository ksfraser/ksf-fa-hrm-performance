<?php
declare(strict_types=1);
require_once __DIR__ . '/ComposerDependencies.php';
\Ksfraser\FrontAccounting\HRM\Performance\Utils\ComposerDependencies::ensure(__DIR__);
$autoload_path = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) { require_once $autoload_path; }
require_once __DIR__ . '/includes/common.inc';
require_once __DIR__ . '/includes/events.inc';
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
define('SS_ksf_FA_HRM_Performance', 191 << 8);
if (!defined('SA_HRM_PERFORMANCE_VIEW')) {
    define('SA_HRM_PERFORMANCE_VIEW', SS_ksf_FA_HRM_Performance | 1);
    define('SA_HRM_PERFORMANCE_MANAGE', SS_ksf_FA_HRM_Performance | 2);
    define('SA_HRM_PERFORMANCE_REVIEW', SS_ksf_FA_HRM_Performance | 3);
    define('SA_HRM_PERFORMANCE_ADMIN', SS_ksf_FA_HRM_Performance | 4);
}
class hooks_ksf_FA_HRM_Performance extends hooks {
    const RESPONDERS = array('RELATED_TO_FIELDS', 'PERFORMANCE_RESOLVE', 'PERFORMANCE_FIELDS', 'PERFORMANCE_LIST');
    var $module_name = 'ksf_FA_HRM_Performance';
    var $version = '1.0.0';
    function install_tabs($app) { }
    function install_options($app) { }
    function install_access() {
        $security_sections[SS_ksf_FA_HRM_Performance] = _("Performance Management");
        $security_areas['SA_HRM_PERFORMANCE_VIEW'] = array(SA_HRM_PERFORMANCE_VIEW, _("View performance reviews"));
        $security_areas['SA_HRM_PERFORMANCE_MANAGE'] = array(SA_HRM_PERFORMANCE_MANAGE, _("Manage performance reviews"));
        $security_areas['SA_HRM_PERFORMANCE_REVIEW'] = array(SA_HRM_PERFORMANCE_REVIEW, _("Conduct reviews"));
        $security_areas['SA_HRM_PERFORMANCE_ADMIN'] = array(SA_HRM_PERFORMANCE_ADMIN, _("Administer performance"));
        return array($security_areas, $security_sections);
    }
    function activate_extension($company, $check_only = true) {
        if ($check_only) { return true; }
        \Ksfraser\FrontAccounting\HRM\Performance\Utils\ComposerDependencies::ensure(__DIR__);
        $this->update_databases($company, array(
            '0_hrm_performance_reviews.sql' => array('hrm_performance_reviews'),
        ), false);
        return true;
    }
    private function ensure_composer_dependencies() {
        \Ksfraser\FrontAccounting\HRM\Performance\Utils\ComposerDependencies::ensure(__DIR__);
    }
    public function getModuleConstants(&$data, $opts = null) {
        $data['constants'] = array(
            'SS_ksf_FA_HRM_Performance' => SS_ksf_FA_HRM_Performance,
            'SA_HRM_PERFORMANCE_VIEW' => SA_HRM_PERFORMANCE_VIEW,
            'SA_HRM_PERFORMANCE_MANAGE' => SA_HRM_PERFORMANCE_MANAGE,
            'SA_HRM_PERFORMANCE_REVIEW' => SA_HRM_PERFORMANCE_REVIEW,
            'SA_HRM_PERFORMANCE_ADMIN' => SA_HRM_PERFORMANCE_ADMIN,
        );
        return true;
    }
    public function getModuleCapabilities(&$data, $opts = null) {
        $data['capabilities'] = self::RESPONDERS;
        return true;
    }
    public function hasCapability(&$data, $opts) {
        $capability = is_array($opts) && isset($opts['capability']) ? $opts['capability'] : null;
        if ($capability === null && isset($data['capability'])) { $capability = $data['capability']; }
        $data['has_capability'] = in_array($capability, self::RESPONDERS, true);
        return $data['has_capability'];
    }
    public function respondToCapabilityRequest(&$data, $opts = null) {
        $request = is_array($opts) && isset($opts['request']) ? $opts['request'] : (isset($data['request']) ? $data['request'] : 'capabilities');
        $data['request'] = $request;
        $data['module'] = $this->module_name;
        if (strpos($request, 'has:') === 0) { return $this->hasCapability($data, array('capability' => substr($request, 4))); }
        switch ($request) {
            case 'capabilities': return $this->getModuleCapabilities($data, $opts);
            case 'constants': return $this->getModuleConstants($data, $opts);
            default: $data['error'] = 'Unknown request type: ' . $request; return null;
        }
    }
    public function RELATED_TO_FIELDS(&$data, $opts = null) {
        if (!isset($data['module']) || !is_string($data['module'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[module]'; return null; }
        if (!isset($data['type']) || !is_string($data['type'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[type]'; return null; }
        if ($data['type'] !== 'hrm_performance_review') { return null; }
        if (!isset($data['fields']) || !is_array($data['fields'])) { $data['fields'] = array(); }
        $data['fields'] = array_merge($data['fields'], array(
            'review_id' => _('Review ID'),
            'person_id' => _('Employee'),
            'reviewer_id' => _('Reviewer'),
            'review_period' => _('Review Period'),
            'rating' => _('Rating'),
            'state' => _('Status'),
        ));
        return null;
    }
    public function PERFORMANCE_RESOLVE(&$data, $opts = null) {
        if (!isset($data['review_id']) || !is_numeric($data['review_id'])) { $data['error'] = 'PERFORMANCE_RESOLVE requires data[review_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'PERFORMANCE_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\Performance\Service\PerformanceService();
        $data['result'] = $service->resolveById((int) $data['review_id'], $data['dto']);
        return null;
    }
    public function PERFORMANCE_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'review_id' => _('Review ID'),
            'person_id' => _('Employee'),
            'reviewer_id' => _('Reviewer'),
            'review_period' => _('Review Period'),
            'rating' => _('Rating'),
            'state' => _('Status'),
        );
        return null;
    }
    public function PERFORMANCE_LIST(&$data, $opts = null) {
        if (!isset($data['person_id']) || !is_numeric($data['person_id'])) { $data['error'] = 'PERFORMANCE_LIST requires data[person_id]'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\Performance\Service\PerformanceService();
        $data['result'] = $service->listForPerson((int) $data['person_id']);
        return null;
    }
}
