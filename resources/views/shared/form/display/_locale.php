<?php

// Check whether the UI should be displayed
if (!$localize || $localize->hidden()) { return;
}

// Create radios config
$config = Bkwld\Library\Laravel\Former::radioArray(Config::get('sitec.site.locales'));

// Look for other localizations of this record
if ($item && ($localizations = $localize->other())) {
    $original = $config;
    $config = [];

    // Group other ones by their locale slug and loop though
    $localizations = $localizations->groupBy('locale');
    foreach($original as $label => $options) {

        // If a locale is already in use, disable it
        if ($sibling = $localizations->get($options['value'])) {
            $sibling = $sibling[0]; // The groupBy makes an array for its value
            $options['disabled'] = true;
            $label = "<span class='locale-label'>{$label} - " . __('facilitador::display.locale.localized_as') . " <a href='".FacilitadorURL::relative('edit', $sibling->getKey())."'>".$sibling->admin_title.'</a></span>';
            $config[$label] = $options;

            // Else, don't touch
        } else { $config[$label] = $options;
        }
    }
}

// Render the locale menu
echo Former::radios('locale', __('facilitador::display.locale.label'))
    ->radios($config)
    ->addGroupClass('locale')
    ->blockHelp(__('facilitador::display.locale.help'));
