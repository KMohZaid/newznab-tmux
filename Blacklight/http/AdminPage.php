<?php

namespace Blacklight\http;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

/**
 * All admin pages implement this class. Enforces admin role for requesting user.
 */
class AdminPage extends BasePage
{
    /**
     * Default constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        // Tell Smarty which directories to use for templates
        $this->smarty->setTemplateDir(
            [
                'admin'    => config('ytake-laravel-smarty.template_path').'/admin',
                'shared'    => config('ytake-laravel-smarty.template_path').'/shared',
                'default'    => config('ytake-laravel-smarty.template_path').'/admin',
            ]
        );

        if (! Auth::check()) {
            $this->show403(true);
        }

        $this->smarty->assign('catClass', Category::class);
    }

    /**
     * Output a page using the admin template.
     *
     * @throws \Exception
     */
    public function render(): void
    {
        $this->smarty->assign('page', $this);

        $admin_menu = $this->smarty->fetch('adminmenu.tpl');
        $this->smarty->assign('admin_menu', $admin_menu);

        $this->page_template = 'baseadminpage.tpl';

        parent::render();
    }
}
