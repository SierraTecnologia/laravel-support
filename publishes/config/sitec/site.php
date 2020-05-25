<?php

return [

    /**
     * The name of the site is shown in the header of all pages
     *
     * @var string
     */
    'name' => \Illuminate\Support\Facades\Config::get('app.name', 'SiravelAdmin'),

    /**
     * After a succesful login, this is the absolute path or url that should be
     * redirected to.  Make falsey to redirect to the first page in the nav
     *
     * @var callable|string|null
     */
    // 'post_login_redirect' => '/siravel/admins',

    /**
     * Roles that super admins can assign other admins to on the admin edit page.
     * If left empty, all admins will be assigned to the default level of "admin".
     *
     * @var array
     */
    'roles' => [
        // 'super' => '<b>Super admin</b> - Can manage all content.',
        // 'general' => '<b>General</b> - Can manage sub pages of services and buildings (except for forms).',
        // 'forms' => '<b>Forms</b> - Can do everything a general admin can but can also manage forms.',
    ],

    /**
     * Permissions rules.  These are described in more detail in the README.
     *
     * @var array
     */
    'permissions' => [
        // 'general' => [
        //     'cant' => [
        //         'create.categories',
        //         'destroy.categories',
        //         'manage.slides',
        //         'manage.sub-categories',
        //         'manage.forms',
        //     ],
        // ],
    ],

    /**
     * A hash of localization slugs and readable labels for all the locales for this
     * site.  Localization UI will only appear if the count > 1.
     *
     * @var array
     */
    'locales' => [
        'en' => 'English',
        'pt' => 'Português',
        // 'es' => 'Spanish',
        // 'fr' => 'French',
    ],

    /**
     * Automatically apply localization options to all models that at the root
     * level in the nav.  The thinking is that a site that is localized should
     * have everything localized but that children will inherit the localization
     * preference from a parent.
     *
     * @var boolean
     */
    'auto_localize_root_models' => true,

    /**
     * Store an entry in the database of all model changes.  Also see the
     * shouldLogChange() function that can be overriden per-model
     *
     * @var boolean
     */
    'log_changes' => true,

];
