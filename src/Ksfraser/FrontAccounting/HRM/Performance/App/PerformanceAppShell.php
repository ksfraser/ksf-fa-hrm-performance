<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Performance\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\App\TabRegistration;

class PerformanceAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('performance', 'index.php', 'view', 'performance_reviews');

        $this->registerTab(new TabRegistration(
            'performance_reviews',
            'Performance Reviews',
            'SA_PERFORMANCE_VIEW',
            null,
            10,
            0,
            null,
            null,
            array()
        ));
    }
}
