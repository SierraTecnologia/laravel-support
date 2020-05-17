<?php

namespace Support\Template\Layout;

use URL;
use Config;

/**
 * Generate an array for the nav that is more easily parsed in a frontend view
 */
class Nav
{
    /**
     * Generate the nav config
     *
     * @return array
     */
    public function generate()
    {
        // Get the navigation pages from the config
        $pages = (new \Support\Template\Mounters\SystemMount())->loadMenuForArray();
        if (is_callable($pages)) {
            $pages = call_user_func($pages);
        }

        // Loop through the list of pages and massage
        $massaged = [];
        foreach ($pages as $page) {
            // dd($pages, $page);
            $massaged[] = $this->recursiveGenerate($page);
        }
        // dd($massaged);
        return $massaged;
    }




    /**
     * Generate the nav config
     *
     * @return array
     */
    public function recursiveGenerate($menuItem)
    {
        // Ignora Caso Seja Um Divisor
        if (is_string($menuItem)) {
            return $menuItem;
            // return ['divider' => true];
        }

        // Create a new page instance that represents the dropdown menu
        $page = [
            'label' => $menuItem['text'],
            'active' => false,
            'diviser' => false
        ];
        if (isset($menuItem['icon'])) {
            $page['icon'] = $menuItem['icon'];
        }
        if (isset($menuItem['url'])) {
            $page['url'] = $menuItem['url'];
        } else if (isset($menuItem['route'])) {
            // if (!empty($menuItem['route']) && !Route::has($menuItem['route'])) {
            $page['url'] = route($menuItem['route']);
        }

        // If menuItem is an array, make a drop down menu
        if (is_array($menuItem) && isset($menuItem['submenu']) && !empty($menuItem['submenu'])) {
            $page['children'] = [];
            // Loop through children (we only support one level deep) and
            // add each as a child
            foreach ($menuItem['submenu'] as $menuSubItem) {
                $page['children'][] = $this->recursiveGenerate($menuSubItem);
            }
            // See if any of the children are active and set the pulldown to active
            foreach ($page['children'] as $child) {
                if (!empty($child->active)) {
                    $page['active'] = true;
                    break;
                }
            }
        }

        return (object) $page;
    }
    /**
     * Nao usado mais faz da forma antiga
     *
     * @return array
     */
    public function oldGenerate()
    {
        // Get the navigation pages from the config
        $pages = (new \Support\Template\Mounters\SystemMount())->loadMenuForArray();
        if (is_callable($pages)) {
            $pages = call_user_func($pages);
        }

        // Loop through the list of pages and massage
        $massaged = [];
        foreach ($pages as $key => $val) {
            dd($pages, $key, $val);
            // If val is an array, make a drop down menu
            if (is_array($val)) {

                // Create a new page instance that represents the dropdown menu
                $page = ['active' => false];
                $page = array_merge($page, $this->makeIcon($key));
                $page['children'] = [];

                // Loop through children (we only support one level deep) and
                // add each as a child
                foreach ($val as $child_key => $child_val) {
                    $page['children'][] = $this->makePage($child_key, $child_val);
                }

                // See if any of the children are active and set the pulldown to active
                foreach ($page['children'] as $child) {
                    if (!empty($child->active)) {
                        $page['active'] = true;
                        break;
                    }
                }

                // Add the pulldown to the list of pages
                $massaged[] = (object) $page;

                // The page is a simple (non pulldown) link
            } else {
                $massaged[] = $this->makePage($key, $val);
            }
        }

        // Pass along the navigation data
        return $massaged;
    }
    /**
     * Break the icon out of the label, returning an arary of label and icon
     */
    protected function makeIcon($label_and_icon)
    {
        $parts = explode(',', $label_and_icon);
        if (count($parts) == 2) {
            return ['label' => $parts[0], 'icon' => $parts[1]];
        }

        return ['label' => $parts[0], 'icon' => 'default'];
    }
    /**
     * Make a page object
     *
     * @return object
     */
    protected function makePage($key, $val)
    {
        // Check if it's a divider
        if ($val === '-') {
            return (object) ['divider' => true];
        }

        // Create a new page
        $page = ['url' => $val, 'divider' => false];
        $page = array_merge($page, $this->makeIcon($key));

        // Check if this item is the currently selected one
        $page['active'] = false;
        if (strpos(URL::current(), parse_url($page['url'], PHP_URL_PATH))) {
            $page['active'] = true;
        }

        // Return the new page
        return (object) $page;
    }
}
