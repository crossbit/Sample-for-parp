<?php

namespace App\Modules\Basic\Navigation\Utils\Composers\Back;

use App\Modules\Basic\Navigation\Traits\LoadNavigation;
use Illuminate\View\View;

class NavigationComposer
{
    use LoadNavigation;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('backNavigation', $this->loadNavigation('back'));
    }

}