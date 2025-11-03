<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Controllers\SidebarController;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Tự động inject popularCategories vào tất cả views
        $view->with('popularCategories', SidebarController::getSidebarData());
    }
}









