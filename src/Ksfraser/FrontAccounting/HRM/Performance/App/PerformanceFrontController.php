<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Performance\App;

use ksfraser\FrontAccounting\Common\App\AbstractFrontController;

class PerformanceFrontController extends AbstractFrontController
{
    public function __construct(
        PerformanceAppShell $appShell,
        string $fallbackSecurityArea,
        string $path_to_root
    ) {
        parent::__construct($appShell, $fallbackSecurityArea, $path_to_root);
    }
}
