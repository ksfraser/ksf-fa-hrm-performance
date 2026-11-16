<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Performance\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\Menu\FAModuleMenu;
use Ksfraser\FrontAccounting\HRM\Performance\Controller\PerformanceTabController;

class PerformanceAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('performance', 'index.php', 'view', 'performance_reviews');

        $this->add_module(_("Performance"));

        $menu = new FAModuleMenu(
            'modules/ksf_FA_HRM_Performance/index.php',
            'view',
            ''
        );

        $menu->addItem('performance_reviews', _("Performance Reviews"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_PERFORMANCE_VIEW');

        $this->add_extensions();
    }
}
