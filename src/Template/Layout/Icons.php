<?php

namespace Support\Template\Layout;

use URL;
use Request;
use Facilitador\Routing\Wildcard;
use Support\Helpers\Words\ReturnSimilars;

/**
 * Generate default breadcrumbs and provide a store where they can be
 * overridden before rendering.
 */
class Icons
{
        public static function getRandon()
        {
            $icon = array_rand(self::icons(), 1);
            return '<i class="'.self::icons()[$icon]['class'].'"></i>';
        }

        public static function getForNameAndCache($name)
        {
            $name = ReturnSimilars::getSimilarsFor($name);
            $icons = collect(self::icons())->reject(function ($icon) use ($name) {
                $reject = true;
                if (is_string($name)) {
                    $name = [$name];
                }
                foreach ($name as $searchName) {

                    // Procura na class
                    if (isset($icon['class']) && strpos($icon['class'], $searchName) !== false) {
                        $reject = false;
                    }
                    // Procura no nome
                    if (isset($icon['name']) && strpos($icon['name'], $searchName) !== false) {
                        $reject = false;
                    }

                    // Procura no uses
                    if (!isset($icon['uses']) || !is_array($icon['uses']) || empty($icon['uses'])) {
                        continue;
                    }
                    if (in_array($searchName, $icon['uses'])) {
                        $reject = false;
                    }
                }

                return $reject;
            });
            
            if (empty($icons)) {
                return $this->getRandon();
            }
            
            if (empty($icons)) {
                return $this->getRandon();
            }

            $icon = array_rand(self::icons(), 1);
            return '<i class="'.self::icons()[$icon]['class'].'"></i>';
        }


        public static function icons() {
                  return [


        // public function icons('66 New Icons in 4.4') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-500px',
                        'name' => 'fa-500px',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-amazon',
                        'name' => 'fa-amazon',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-balance-scale',
                        'name' => 'fa-balance-scale',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-0',
                        'name' => 'fa-battery-0',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-1',
                        'name' => 'fa-battery-1',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-2',
                        'name' => 'fa-battery-2',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-3',
                        'name' => 'fa-battery-3',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-4',
                        'name' => 'fa-battery-4',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-empty',
                        'name' => 'fa-battery-empty',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-full',
                        'name' => 'fa-battery-full',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-half',
                        'name' => 'fa-battery-half',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-quarter',
                        'name' => 'fa-battery-quarter',
                        'uses' => [

                        ],
                    ],
                    [
                    'class' => 'fa fa-fw fa-battery-three-quarters',
                    'name' => 'fa-battery-three-quarters',
                        'uses' => [

                        ],
                ],
                    
                    [
                        'class' => 'fa fa-fw fa-black-tie',
                        'name' => 'fa-black-tie',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-calendar-check-o',
                        'name' => 'fa-calendar-check-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-calendar-minus-o',
                        'name' => 'fa-calendar-minus-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-calendar-plus-o',
                        'name' => 'fa-calendar-plus-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-calendar-times-o',
                        'name' => 'fa-calendar-times-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-cc-diners-club',
                        'name' => 'fa-cc-diners-club',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-jcb',
                        'name' => 'fa-cc-jcb',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chrome',
                        'name' => 'fa-chrome',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-clone',
                        'name' => 'fa-clone',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-commenting',
                        'name' => 'fa-commenting',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-commenting-o',
                        'name' => 'fa-commenting-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-contao',
                        'name' => 'fa-contao',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-creative-commons',
                        'name' => 'fa-creative-commons',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-expeditedssl',
                        'name' => 'fa-expeditedssl',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-firefox',
                        'name' => 'fa-firefox',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fonticons',
                        'name' => 'fa-fonticons',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-genderless',
                        'name' => 'fa-genderless',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-get-pocket',
                        'name' => 'fa-get-pocket',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gg',
                        'name' => 'fa-gg',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gg-circle',
                        'name' => 'fa-gg-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-grab-o',
                        'name' => 'fa-hand-grab-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hand-lizard-o',
                        'name' => 'fa-hand-lizard-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-paper-o',
                        'name' => 'fa-hand-paper-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-peace-o',
                        'name' => 'fa-hand-peace-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-pointer-o',
                        'name' => 'fa-hand-pointer-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-rock-o',
                        'name' => 'fa-hand-rock-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-scissors-o',
                        'name' => 'fa-hand-scissors-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-spock-o',
                        'name' => 'fa-hand-spock-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-stop-o',
                        'name' => 'fa-hand-stop-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hourglass',
                        'name' => 'fa-hourglass',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-1',
                        'name' => 'fa-hourglass-1',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hourglass-2',
                        'name' => 'fa-hourglass-2',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hourglass-3',
                        'name' => 'fa-hourglass-3',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hourglass-end',
                        'name' => 'fa-hourglass-end',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-half',
                        'name' => 'fa-hourglass-half',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-o',
                        'name' => 'fa-hourglass-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-start',
                        'name' => 'fa-hourglass-start',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-houzz',
                        'name' => 'fa-houzz',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-i-cursor',
                        'name' => 'fa-i-cursor',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-industry',
                        'name' => 'fa-industry',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-internet-explorer',
                        'name' => 'fa-internet-explorer',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-map',
                        'name' => 'fa-map',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map-o',
                        'name' => 'fa-map-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map-pin',
                        'name' => 'fa-map-pin',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map-signs',
                        'name' => 'fa-map-signs',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mouse-pointer',
                        'name' => 'fa-mouse-pointer',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-object-group',
                        'name' => 'fa-object-group',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-object-ungroup',
                        'name' => 'fa-object-ungroup',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-odnoklassniki',
                        'name' => 'fa-odnoklassniki',
                        'uses' => [

                        ],
                    ],
                    [
                    'class' => 'fa fa-fw fa-odnoklassniki-square',
                    'name' => 'fa-odnoklassniki-square',
                        'uses' => [

                        ],
                ],
                    
                    [
                        'class' => 'fa fa-fw fa-opencart',
                        'name' => 'fa-opencart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-opera',
                        'name' => 'fa-opera',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-optin-monster',
                        'name' => 'fa-optin-monster',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-registered',
                        'name' => 'fa-registered',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-safari',
                        'name' => 'fa-safari',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sticky-note',
                        'name' => 'fa-sticky-note',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sticky-note-o',
                        'name' => 'fa-sticky-note-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-television',
                        'name' => 'fa-television',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-trademark',
                        'name' => 'fa-trademark',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tripadvisor',
                        'name' => 'fa-tripadvisor',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tv',
                        'name' => 'fa-tv',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-vimeo',
                        'name' => 'fa-vimeo',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wikipedia-w',
                        'name' => 'fa-wikipedia-w',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-y-combinator',
                        'name' => 'fa-y-combinator',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-yc',
                        'name' => 'fa-yc',
                        'uses' => [

                        ],
                    ],
                     // Alias
                  
            //     ];
            // }

                // <section id="web-application">
                  
                
        // public function icons('Web Application Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-adjust',
                        'name' => 'fa-adjust',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-anchor',
                        'name' => 'fa-anchor',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-archive',
                        'name' => 'fa-archive',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-area-chart',
                        'name' => 'fa-area-chart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrows',
                        'name' => 'fa-arrows',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrows-h',
                        'name' => 'fa-arrows-h',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrows-v',
                        'name' => 'fa-arrows-v',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-asterisk',
                        'name' => 'fa-asterisk',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-at',
                        'name' => 'fa-at',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-automobile',
                        'name' => 'fa-automobile',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-balance-scale',
                        'name' => 'fa-balance-scale',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ban',
                        'name' => 'fa-ban',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-bank',
                    //     'name' => 'fa-bank <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-bar-chart',
                        'name' => 'fa-bar-chart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bar-chart-o',
                        'name' => 'fa-bar-chart-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-barcode',
                        'name' => 'fa-barcode',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bars',
                        'name' => 'fa-bars',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-0',
                        'name' => 'fa-battery-0',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-1',
                        'name' => 'fa-battery-1',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-2',
                        'name' => 'fa-battery-2',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-3',
                        'name' => 'fa-battery-3',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-4',
                        'name' => 'fa-battery-4',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-battery-empty',
                        'name' => 'fa-battery-empty',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-full',
                        'name' => 'fa-battery-full',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-half',
                        'name' => 'fa-battery-half',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-battery-quarter',
                        'name' => 'fa-battery-quarter',
                        'uses' => [

                        ],
                    ],
                    [
                    'class' => 'fa fa-fw fa-battery-three-quarters',
                    'name' => 'fa-battery-three-quarters',
                        'uses' => [

                        ],
                ],
                    
                    [
                        'class' => 'fa fa-fw fa-bed',
                        'name' => 'fa-bed',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-beer',
                        'name' => 'fa-beer',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bell',
                        'name' => 'fa-bell',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bell-o',
                        'name' => 'fa-bell-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bell-slash',
                        'name' => 'fa-bell-slash',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bell-slash-o',
                        'name' => 'fa-bell-slash-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bicycle',
                        'name' => 'fa-bicycle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-binoculars',
                        'name' => 'fa-binoculars',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-birthday-cake',
                        'name' => 'fa-birthday-cake',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bolt',
                        'name' => 'fa-bolt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bomb',
                        'name' => 'fa-bomb',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-book',
                        'name' => 'fa-book',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bookmark',
                        'name' => 'fa-bookmark',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bookmark-o',
                        'name' => 'fa-bookmark-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-briefcase',
                        'name' => 'fa-briefcase',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bug',
                        'name' => 'fa-bug',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-building',
                        'name' => 'fa-building',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-building-o',
                        'name' => 'fa-building-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bullhorn',
                        'name' => 'fa-bullhorn',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bullseye',
                        'name' => 'fa-bullseye',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bus',
                        'name' => 'fa-bus',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-cab',
                    //     'name' => 'fa-cab <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-calculator',
                        'name' => 'fa-calculator',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-calendar',
                        'name' => 'fa-calendar',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-calendar-check-o',
                        'name' => 'fa-calendar-check-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-calendar-minus-o',
                        'name' => 'fa-calendar-minus-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-calendar-o',
                        'name' => 'fa-calendar-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-calendar-plus-o',
                        'name' => 'fa-calendar-plus-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-calendar-times-o',
                        'name' => 'fa-calendar-times-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-camera',
                        'name' => 'fa-camera',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-camera-retro',
                        'name' => 'fa-camera-retro',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-car',
                        'name' => 'fa-car',
                        'uses' => [

                        ],
                    ],
                    [
                    'class' => 'fa fa-fw fa-caret-square-o-down',
                    'name' => 'fa-caret-square-o-down',
                        'uses' => [

                        ],
                ],
                    [
                    'class' => 'fa fa-fw fa-caret-square-o-left',
                    'name' => 'fa-caret-square-o-left',
                        'uses' => [

                        ],
                ],
                    [
                    'class' => 'fa fa-fw fa-caret-square-o-right',
                    'name' => 'fa-caret-square-o-right',
                        'uses' => [

                        ],
                ],
                    [
                        'class' => 'fa fa-fw fa-caret-square-o-up',
                        'name' => 'fa-caret-square-o-up',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-cart-arrow-down',
                        'name' => 'fa-cart-arrow-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cart-plus',
                        'name' => 'fa-cart-plus',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc',
                        'name' => 'fa-cc',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-certificate',
                        'name' => 'fa-certificate',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-check',
                        'name' => 'fa-check',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-check-circle',
                        'name' => 'fa-check-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-check-circle-o',
                        'name' => 'fa-check-circle-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-check-square',
                        'name' => 'fa-check-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-check-square-o',
                        'name' => 'fa-check-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-child',
                        'name' => 'fa-child',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-circle',
                        'name' => 'fa-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-circle-o',
                        'name' => 'fa-circle-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-circle-o-notch',
                        'name' => 'fa-circle-o-notch',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-circle-thin',
                        'name' => 'fa-circle-thin',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-clock-o',
                        'name' => 'fa-clock-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-clone',
                        'name' => 'fa-clone',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-close',
                    //     'name' => 'fa-close <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-cloud',
                        'name' => 'fa-cloud',
                        'uses' => [

                        ],
                    ],
                    // @todo Não funcionando (Em Brando)
                    // [
                    //     'class' => 'fa fa-fw fa-cloud-download',
                    //     'name' => 'fa-cloud-download',
                        'uses' => [

                        ],
                    // ],
                    [
                        'class' => 'fa fa-fw fa-cloud-upload',
                        'name' => 'fa-cloud-upload',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-code',
                        'name' => 'fa-code',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-code-fork',
                        'name' => 'fa-code-fork',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-coffee',
                        'name' => 'fa-coffee',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cog',
                        'name' => 'fa-cog',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cogs',
                        'name' => 'fa-cogs',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-comment',
                        'name' => 'fa-comment',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-comment-o',
                        'name' => 'fa-comment-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-commenting',
                        'name' => 'fa-commenting',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-commenting-o',
                        'name' => 'fa-commenting-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-comments',
                        'name' => 'fa-comments',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-comments-o',
                        'name' => 'fa-comments-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-compass',
                        'name' => 'fa-compass',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-copyright',
                        'name' => 'fa-copyright',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-creative-commons',
                        'name' => 'fa-creative-commons',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-credit-card',
                        'name' => 'fa-credit-card',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-crop',
                        'name' => 'fa-crop',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-crosshairs',
                        'name' => 'fa-crosshairs',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cube',
                        'name' => 'fa-cube',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cubes',
                        'name' => 'fa-cubes',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cutlery',
                        'name' => 'fa-cutlery',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-dashboard',
                        'name' => 'fa-dashboard',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-database',
                        'name' => 'fa-database',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-desktop',
                        'name' => 'fa-desktop',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-diamond',
                        'name' => 'fa-diamond',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-dot-circle-o',
                        'name' => 'fa-dot-circle-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-download',
                        'name' => 'fa-download',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-edit',
                    //     'name' => 'fa-edit <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-ellipsis-h',
                        'name' => 'fa-ellipsis-h',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ellipsis-v',
                        'name' => 'fa-ellipsis-v',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-envelope',
                        'name' => 'fa-envelope',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-envelope-o',
                        'name' => 'fa-envelope-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-envelope-square',
                        'name' => 'fa-envelope-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-eraser',
                        'name' => 'fa-eraser',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-exchange',
                        'name' => 'fa-exchange',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-exclamation',
                        'name' => 'fa-exclamation',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-exclamation-circle',
                        'name' => 'fa-exclamation-circle',
                        'uses' => [

                        ],
                    ],
                    [
                    'class' => 'fa fa-fw fa-exclamation-triangle',
                    'name' => 'fa-exclamation-triangle',
                        'uses' => [

                        ],
                ],
                    
                    [
                        'class' => 'fa fa-fw fa-external-link',
                        'name' => 'fa-external-link',
                        'uses' => [

                        ],
                    ],
                    [
                    'class' => 'fa fa-fw fa-external-link-square',
                    'name' => 'fa-external-link-square',
                        'uses' => [

                        ],
                ],
                    
                    [
                        'class' => 'fa fa-fw fa-eye',
                        'name' => 'fa-eye',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-eye-slash',
                        'name' => 'fa-eye-slash',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-eyedropper',
                        'name' => 'fa-eyedropper',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fax',
                        'name' => 'fa-fax',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-feed',
                    //     'name' => 'fa-feed <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-female',
                        'name' => 'fa-female',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fighter-jet',
                        'name' => 'fa-fighter-jet',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-archive-o',
                        'name' => 'fa-file-archive-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-audio-o',
                        'name' => 'fa-file-audio-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-code-o',
                        'name' => 'fa-file-code-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-excel-o',
                        'name' => 'fa-file-excel-o',
                        'uses' => [

                        ],
                    ],
                    // @todo Não funcionando (Em Brando)
                    // [
                    //     'class' => 'fa fa-fw fa-file-image-o',
                    //     'name' => 'fa-file-image-o',
                        'uses' => [

                        ],
                    // ],
                    [
                        'class' => 'fa fa-fw fa-file-movie-o',
                        'name' => 'fa-file-movie-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-pdf-o',
                        'name' => 'fa-file-pdf-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-photo-o',
                        'name' => 'fa-file-photo-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-picture-o',
                        'name' => 'fa-file-picture-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-powerpoint-o',
                        'name' => 'fa-file-powerpoint-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-file-sound-o',
                        'name' => 'fa-file-sound-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-video-o',
                        'name' => 'fa-file-video-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-word-o',
                        'name' => 'fa-file-word-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-zip-o',
                        'name' => 'fa-file-zip-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-film',
                        'name' => 'fa-film',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-filter',
                        'name' => 'fa-filter',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fire',
                        'name' => 'fa-fire',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fire-extinguisher',
                        'name' => 'fa-fire-extinguisher',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-flag',
                        'name' => 'fa-flag',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-flag-checkered',
                        'name' => 'fa-flag-checkered',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-flag-o',
                        'name' => 'fa-flag-o',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-flash',
                    //     'name' => 'fa-flash <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-flask',
                        'name' => 'fa-flask',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-folder',
                        'name' => 'fa-folder',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-folder-o',
                        'name' => 'fa-folder-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-folder-open',
                        'name' => 'fa-folder-open',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-folder-open-o',
                        'name' => 'fa-folder-open-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-frown-o',
                        'name' => 'fa-frown-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-futbol-o',
                        'name' => 'fa-futbol-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gamepad',
                        'name' => 'fa-gamepad',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gavel',
                        'name' => 'fa-gavel',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-gear',
                    //     'name' => 'fa-gear <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    // [
                    //     'class' => 'fa fa-fw fa-gears',
                    //     'name' => 'fa-gears <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-gift',
                        'name' => 'fa-gift',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-glass',
                        'name' => 'fa-glass',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-globe',
                        'name' => 'fa-globe',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-graduation-cap',
                        'name' => 'fa-graduation-cap',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-group',
                    //     'name' => 'fa-group <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-hand-grab-o',
                        'name' => 'fa-hand-grab-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hand-lizard-o',
                        'name' => 'fa-hand-lizard-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-paper-o',
                        'name' => 'fa-hand-paper-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-peace-o',
                        'name' => 'fa-hand-peace-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-pointer-o',
                        'name' => 'fa-hand-pointer-o',
                        'uses' => [

                        ],
                    ],
                    // @todo Não funcionando (Em Brando)
                    // [
                    //     'class' => 'fa fa-fw fa-hand-rock-o',
                    //     'name' => 'fa-hand-rock-o',
                        'uses' => [

                        ],
                    // ],
                    [
                        'class' => 'fa fa-fw fa-hand-scissors-o',
                        'name' => 'fa-hand-scissors-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-spock-o',
                        'name' => 'fa-hand-spock-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-stop-o',
                        'name' => 'fa-hand-stop-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hdd-o',
                        'name' => 'fa-hdd-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-headphones',
                        'name' => 'fa-headphones',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-heart',
                        'name' => 'fa-heart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-heart-o',
                        'name' => 'fa-heart-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-heartbeat',
                        'name' => 'fa-heartbeat',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-history',
                        'name' => 'fa-history',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-home',
                        'name' => 'fa-home',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-hotel',
                    //     'name' => 'fa-hotel <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-hourglass',
                        'name' => 'fa-hourglass',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-1',
                        'name' => 'fa-hourglass-1',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hourglass-2',
                        'name' => 'fa-hourglass-2',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hourglass-3',
                        'name' => 'fa-hourglass-3',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hourglass-end',
                        'name' => 'fa-hourglass-end',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-half',
                        'name' => 'fa-hourglass-half',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-o',
                        'name' => 'fa-hourglass-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hourglass-start',
                        'name' => 'fa-hourglass-start',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-i-cursor',
                        'name' => 'fa-i-cursor',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-image',
                    //     'name' => 'fa-image <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-inbox',
                        'name' => 'fa-inbox',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-industry',
                        'name' => 'fa-industry',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-info',
                        'name' => 'fa-info',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-info-circle',
                        'name' => 'fa-info-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-institution',
                        'name' => 'fa-institution',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-key',
                        'name' => 'fa-key',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-keyboard-o',
                        'name' => 'fa-keyboard-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-language',
                        'name' => 'fa-language',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-laptop',
                        'name' => 'fa-laptop',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-leaf',
                        'name' => 'fa-leaf',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-legal',
                    //     'name' => 'fa-legal <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-lemon-o',
                        'name' => 'fa-lemon-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-level-down',
                        'name' => 'fa-level-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-level-up',
                        'name' => 'fa-level-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-life-bouy',
                        'name' => 'fa-life-bouy',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-life-buoy',
                        'name' => 'fa-life-buoy',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-life-ring',
                        'name' => 'fa-life-ring',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-life-saver',
                        'name' => 'fa-life-saver',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-lightbulb-o',
                        'name' => 'fa-lightbulb-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-line-chart',
                        'name' => 'fa-line-chart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-location-arrow',
                        'name' => 'fa-location-arrow',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-lock',
                        'name' => 'fa-lock',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-magic',
                        'name' => 'fa-magic',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-magnet',
                        'name' => 'fa-magnet',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mail-forward',
                        'name' => 'fa-mail-forward',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-mail-reply',
                        'name' => 'fa-mail-reply',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-mail-reply-all',
                        'name' => 'fa-mail-reply-all',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-male',
                        'name' => 'fa-male',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map',
                        'name' => 'fa-map',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map-marker',
                        'name' => 'fa-map-marker',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map-o',
                        'name' => 'fa-map-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map-pin',
                        'name' => 'fa-map-pin',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-map-signs',
                        'name' => 'fa-map-signs',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-meh-o',
                        'name' => 'fa-meh-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-microphone',
                        'name' => 'fa-microphone',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-microphone-slash',
                        'name' => 'fa-microphone-slash',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-minus',
                        'name' => 'fa-minus',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-minus-circle',
                        'name' => 'fa-minus-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-minus-square',
                        'name' => 'fa-minus-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-minus-square-o',
                        'name' => 'fa-minus-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mobile',
                        'name' => 'fa-mobile',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mobile-phone',
                        'name' => 'fa-mobile-phone',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-money',
                        'name' => 'fa-money',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-moon-o',
                        'name' => 'fa-moon-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mortar-board',
                        'name' => 'fa-mortar-board',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-motorcycle',
                        'name' => 'fa-motorcycle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mouse-pointer',
                        'name' => 'fa-mouse-pointer',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-music',
                        'name' => 'fa-music',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-navicon',
                        'name' => 'fa-navicon',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-newspaper-o',
                        'name' => 'fa-newspaper-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-object-group',
                        'name' => 'fa-object-group',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-object-ungroup',
                        'name' => 'fa-object-ungroup',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paint-brush',
                        'name' => 'fa-paint-brush',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paper-plane',
                        'name' => 'fa-paper-plane',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paper-plane-o',
                        'name' => 'fa-paper-plane-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paw',
                        'name' => 'fa-paw',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pencil',
                        'name' => 'fa-pencil',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pencil-square',
                        'name' => 'fa-pencil-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pencil-square-o',
                        'name' => 'fa-pencil-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-phone',
                        'name' => 'fa-phone',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-phone-square',
                        'name' => 'fa-phone-square',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-photo',
                    //     'name' => 'fa-photo <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-picture-o',
                        'name' => 'fa-picture-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pie-chart',
                        'name' => 'fa-pie-chart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plane',
                        'name' => 'fa-plane',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plug',
                        'name' => 'fa-plug',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plus',
                        'name' => 'fa-plus',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plus-circle',
                        'name' => 'fa-plus-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plus-square',
                        'name' => 'fa-plus-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plus-square-o',
                        'name' => 'fa-plus-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-power-off',
                        'name' => 'fa-power-off',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-print',
                        'name' => 'fa-print',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-puzzle-piece',
                        'name' => 'fa-puzzle-piece',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-qrcode',
                        'name' => 'fa-qrcode',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-question',
                        'name' => 'fa-question',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-question-circle',
                        'name' => 'fa-question-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-quote-left',
                        'name' => 'fa-quote-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-quote-right',
                        'name' => 'fa-quote-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-random',
                        'name' => 'fa-random',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-recycle',
                        'name' => 'fa-recycle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-refresh',
                        'name' => 'fa-refresh',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-registered',
                        'name' => 'fa-registered',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-remove',
                        'name' => 'fa-remove',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-reorder',
                        'name' => 'fa-reorder',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-reply',
                        'name' => 'fa-reply',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-reply-all',
                        'name' => 'fa-reply-all',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-retweet',
                        'name' => 'fa-retweet',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-road',
                        'name' => 'fa-road',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-rocket',
                        'name' => 'fa-rocket',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-rss',
                        'name' => 'fa-rss',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-rss-square',
                        'name' => 'fa-rss-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-search',
                        'name' => 'fa-search',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-search-minus',
                        'name' => 'fa-search-minus',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-search-plus',
                        'name' => 'fa-search-plus',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-send',
                    //     'name' => 'fa-send <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-send-o',
                        'name' => 'fa-send-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-server',
                        'name' => 'fa-server',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-share',
                        'name' => 'fa-share',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-share-alt',
                        'name' => 'fa-share-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-share-alt-square',
                        'name' => 'fa-share-alt-square',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-share-square',
                        'name' => 'fa-share-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-share-square-o',
                        'name' => 'fa-share-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-shield',
                        'name' => 'fa-shield',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ship',
                        'name' => 'fa-ship',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-shopping-cart',
                        'name' => 'fa-shopping-cart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sign-in',
                        'name' => 'fa-sign-in',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sign-out',
                        'name' => 'fa-sign-out',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-signal',
                        'name' => 'fa-signal',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sitemap',
                        'name' => 'fa-sitemap',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sliders',
                        'name' => 'fa-sliders',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-smile-o',
                        'name' => 'fa-smile-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-soccer-ball-o',
                        'name' => 'fa-soccer-ball-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-sort',
                        'name' => 'fa-sort',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sort-alpha-asc',
                        'name' => 'fa-sort-alpha-asc',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sort-alpha-desc',
                        'name' => 'fa-sort-alpha-desc',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sort-amount-asc',
                        'name' => 'fa-sort-amount-asc',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sort-amount-desc',
                        'name' => 'fa-sort-amount-desc',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-sort-asc',
                        'name' => 'fa-sort-asc',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sort-desc',
                        'name' => 'fa-sort-desc',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sort-down',
                        'name' => 'fa-sort-down',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-sort-numeric-asc',
                        'name' => 'fa-sort-numeric-asc',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-sort-numeric-desc',
                        'name' => 'fa-sort-numeric-desc',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-sort-up',
                        'name' => 'fa-sort-up',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-space-shuttle',
                        'name' => 'fa-space-shuttle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-spinner',
                        'name' => 'fa-spinner',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-spoon',
                        'name' => 'fa-spoon',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-square',
                        'name' => 'fa-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-square-o',
                        'name' => 'fa-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-star',
                        'name' => 'fa-star',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-star-half',
                        'name' => 'fa-star-half',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-star-half-empty',
                        'name' => 'fa-star-half-empty',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-star-half-full',
                        'name' => 'fa-star-half-full',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-star-half-o',
                        'name' => 'fa-star-half-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-star-o',
                        'name' => 'fa-star-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sticky-note',
                        'name' => 'fa-sticky-note',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sticky-note-o',
                        'name' => 'fa-sticky-note-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-street-view',
                        'name' => 'fa-street-view',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-suitcase',
                        'name' => 'fa-suitcase',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sun-o',
                        'name' => 'fa-sun-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-support',
                        'name' => 'fa-support',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-tablet',
                        'name' => 'fa-tablet',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tachometer',
                        'name' => 'fa-tachometer',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tag',
                        'name' => 'fa-tag',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tags',
                        'name' => 'fa-tags',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tasks',
                        'name' => 'fa-tasks',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-taxi',
                        'name' => 'fa-taxi',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-television',
                        'name' => 'fa-television',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-terminal',
                        'name' => 'fa-terminal',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumb-tack',
                        'name' => 'fa-thumb-tack',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumbs-down',
                        'name' => 'fa-thumbs-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumbs-o-down',
                        'name' => 'fa-thumbs-o-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumbs-o-up',
                        'name' => 'fa-thumbs-o-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumbs-up',
                        'name' => 'fa-thumbs-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ticket',
                        'name' => 'fa-ticket',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-times',
                        'name' => 'fa-times',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-times-circle',
                        'name' => 'fa-times-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-times-circle-o',
                        'name' => 'fa-times-circle-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tint',
                        'name' => 'fa-tint',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-toggle-down',
                        'name' => 'fa-toggle-down',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-toggle-left',
                        'name' => 'fa-toggle-left',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-toggle-off',
                        'name' => 'fa-toggle-off',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-toggle-on',
                        'name' => 'fa-toggle-on',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-toggle-right',
                        'name' => 'fa-toggle-right',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-toggle-up',
                        'name' => 'fa-toggle-up',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-trademark',
                        'name' => 'fa-trademark',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-trash',
                        'name' => 'fa-trash',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-trash-o',
                        'name' => 'fa-trash-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tree',
                        'name' => 'fa-tree',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-trophy',
                        'name' => 'fa-trophy',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-truck',
                        'name' => 'fa-truck',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tty',
                        'name' => 'fa-tty',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tv',
                        'name' => 'fa-tv',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-umbrella',
                        'name' => 'fa-umbrella',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-university',
                        'name' => 'fa-university',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-unlock',
                        'name' => 'fa-unlock',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-unlock-alt',
                        'name' => 'fa-unlock-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-unsorted',
                        'name' => 'fa-unsorted',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-upload',
                        'name' => 'fa-upload',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-user',
                        'name' => 'fa-user',
                        'uses' => [
                            'person'
                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-user-plus',
                        'name' => 'fa-user-plus',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-user-secret',
                        'name' => 'fa-user-secret',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-user-times',
                        'name' => 'fa-user-times',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-users',
                        'name' => 'fa-users',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-video-camera',
                        'name' => 'fa-video-camera',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-volume-down',
                        'name' => 'fa-volume-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-volume-off',
                        'name' => 'fa-volume-off',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-volume-up',
                        'name' => 'fa-volume-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-warning',
                        'name' => 'fa-warning',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-wheelchair',
                        'name' => 'fa-wheelchair',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wifi',
                        'name' => 'fa-wifi',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wrench',
                        'name' => 'fa-wrench',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="hand">
                  
                
        // public function icons('Hand Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-hand-grab-o',
                        'name' => 'fa-hand-grab-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-hand-lizard-o',
                        'name' => 'fa-hand-lizard-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-o-down',
                        'name' => 'fa-hand-o-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-o-left',
                        'name' => 'fa-hand-o-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-o-right',
                        'name' => 'fa-hand-o-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-o-up',
                        'name' => 'fa-hand-o-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-paper-o',
                        'name' => 'fa-hand-paper-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-peace-o',
                        'name' => 'fa-hand-peace-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-pointer-o',
                        'name' => 'fa-hand-pointer-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-rock-o',
                        'name' => 'fa-hand-rock-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-scissors-o',
                        'name' => 'fa-hand-scissors-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-spock-o',
                        'name' => 'fa-hand-spock-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-stop-o',
                        'name' => 'fa-hand-stop-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-thumbs-down',
                        'name' => 'fa-thumbs-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumbs-o-down',
                        'name' => 'fa-thumbs-o-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumbs-o-up',
                        'name' => 'fa-thumbs-o-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-thumbs-up',
                        'name' => 'fa-thumbs-up',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="transportation">
                  
                
        // public function icons('Transportation Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-ambulance',
                        'name' => 'fa-ambulance',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-automobile',
                        'name' => 'fa-automobile',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-bicycle',
                        'name' => 'fa-bicycle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bus',
                        'name' => 'fa-bus',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-cab',
                    //     'name' => 'fa-cab <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-car',
                        'name' => 'fa-car',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fighter-jet',
                        'name' => 'fa-fighter-jet',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-motorcycle',
                        'name' => 'fa-motorcycle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plane',
                        'name' => 'fa-plane',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-rocket',
                        'name' => 'fa-rocket',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ship',
                        'name' => 'fa-ship',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-space-shuttle',
                        'name' => 'fa-space-shuttle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-subway',
                        'name' => 'fa-subway',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-taxi',
                        'name' => 'fa-taxi',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-train',
                        'name' => 'fa-train',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-truck',
                        'name' => 'fa-truck',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wheelchair',
                        'name' => 'fa-wheelchair',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="gender">
                  
                
        // public function icons('Gender Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-genderless',
                        'name' => 'fa-genderless',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-intersex',
                        'name' => 'fa-intersex',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-mars',
                        'name' => 'fa-mars',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mars-double',
                        'name' => 'fa-mars-double',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mars-stroke',
                        'name' => 'fa-mars-stroke',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mars-stroke-h',
                        'name' => 'fa-mars-stroke-h',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mars-stroke-v',
                        'name' => 'fa-mars-stroke-v',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-mercury',
                        'name' => 'fa-mercury',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-neuter',
                        'name' => 'fa-neuter',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-transgender',
                        'name' => 'fa-transgender',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-transgender-alt',
                        'name' => 'fa-transgender-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-venus',
                        'name' => 'fa-venus',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-venus-double',
                        'name' => 'fa-venus-double',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-venus-mars',
                        'name' => 'fa-venus-mars',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="file-type">
                  // File Type Icons

                //   return [
                    [
                        'class' => 'fa fa-fw fa-file',
                        'name' => 'fa-file',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-archive-o',
                        'name' => 'fa-file-archive-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-audio-o',
                        'name' => 'fa-file-audio-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-code-o',
                        'name' => 'fa-file-code-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-excel-o',
                        'name' => 'fa-file-excel-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-image-o',
                        'name' => 'fa-file-image-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-movie-o',
                        'name' => 'fa-file-movie-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-o',
                        'name' => 'fa-file-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-pdf-o',
                        'name' => 'fa-file-pdf-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-photo-o',
                        'name' => 'fa-file-photo-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-picture-o',
                        'name' => 'fa-file-picture-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-powerpoint-o',
                        'name' => 'fa-file-powerpoint-o',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-file-sound-o',
                        'name' => 'fa-file-sound-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-file-text',
                        'name' => 'fa-file-text',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-text-o',
                        'name' => 'fa-file-text-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-video-o',
                        'name' => 'fa-file-video-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-word-o',
                        'name' => 'fa-file-word-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-zip-o',
                        'name' => 'fa-file-zip-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                  
            //     ];
            // }

                // <section id="spinner">
                  // Spinner Icons

                //   return [
                    [
                        'class' => 'fa fa-fw fa-circle-o-notch',
                        'name' => 'fa-circle-o-notch',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cog',
                        'name' => 'fa-cog',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-gear',
                    //     'name' => 'fa-gear <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-refresh',
                        'name' => 'fa-refresh',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-spinner',
                        'name' => 'fa-spinner',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="form-control">
                  
                
        // public function icons('Form Control Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-check-square',
                        'name' => 'fa-check-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-check-square-o',
                        'name' => 'fa-check-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-circle',
                        'name' => 'fa-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-circle-o',
                        'name' => 'fa-circle-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-dot-circle-o',
                        'name' => 'fa-dot-circle-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-minus-square',
                        'name' => 'fa-minus-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-minus-square-o',
                        'name' => 'fa-minus-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plus-square',
                        'name' => 'fa-plus-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plus-square-o',
                        'name' => 'fa-plus-square-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-square',
                        'name' => 'fa-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-square-o',
                        'name' => 'fa-square-o',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="payment">
                  
                
        // public function icons('Payment Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-cc-amex',
                        'name' => 'fa-cc-amex',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-diners-club',
                        'name' => 'fa-cc-diners-club',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-discover',
                        'name' => 'fa-cc-discover',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-jcb',
                        'name' => 'fa-cc-jcb',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-mastercard',
                        'name' => 'fa-cc-mastercard',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-paypal',
                        'name' => 'fa-cc-paypal',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-stripe',
                        'name' => 'fa-cc-stripe',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-visa',
                        'name' => 'fa-cc-visa',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-credit-card',
                        'name' => 'fa-credit-card',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-google-wallet',
                        'name' => 'fa-google-wallet',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paypal',
                        'name' => 'fa-paypal',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="chart">
                  
                
        // public function icons('Chart Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-area-chart',
                        'name' => 'fa-area-chart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bar-chart',
                        'name' => 'fa-bar-chart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bar-chart-o',
                        'name' => 'fa-bar-chart-o',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-line-chart',
                        'name' => 'fa-line-chart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pie-chart',
                        'name' => 'fa-pie-chart',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="currency">
                  
                
        // public function icons('Currency Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-bitcoin',
                        'name' => 'fa-bitcoin',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-btc',
                        'name' => 'fa-btc',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-cny',
                    //     'name' => 'fa-cny <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-dollar',
                        'name' => 'fa-dollar',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-eur',
                        'name' => 'fa-eur',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-euro',
                    //     'name' => 'fa-euro <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-gbp',
                        'name' => 'fa-gbp',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gg',
                        'name' => 'fa-gg',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gg-circle',
                        'name' => 'fa-gg-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ils',
                        'name' => 'fa-ils',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-inr',
                        'name' => 'fa-inr',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-jpy',
                        'name' => 'fa-jpy',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-krw',
                        'name' => 'fa-krw',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-money',
                        'name' => 'fa-money',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-rmb',
                    //     'name' => 'fa-rmb <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-rouble',
                        'name' => 'fa-rouble',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    // @todo Não funcionando (Em Brando)
                    // [
                    //     'class' => 'fa fa-fw fa-rub',
                    //     'name' => 'fa-rub',
                        'uses' => [

                        ],
                    // ],
                    // [
                    //     'class' => 'fa fa-fw fa-ruble',
                    //     'name' => 'fa-ruble <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    // [
                    //     'class' => 'fa fa-fw fa-rupee',
                    //     'name' => 'fa-rupee <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-shekel',
                        'name' => 'fa-shekel',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-sheqel',
                        'name' => 'fa-sheqel',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-try',
                        'name' => 'fa-try',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-turkish-lira',
                        'name' => 'fa-turkish-lira',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-usd',
                        'name' => 'fa-usd',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-won',
                    //     'name' => 'fa-won <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    // [
                    //     'class' => 'fa fa-fw fa-yen',
                    //     'name' => 'fa-yen <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                  
            //     ];
            // }

                // <section id="text-editor">
                  
                
        // public function icons('Text Editor Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-align-center',
                        'name' => 'fa-align-center',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-align-justify',
                        'name' => 'fa-align-justify',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-align-left',
                        'name' => 'fa-align-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-align-right',
                        'name' => 'fa-align-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bold',
                        'name' => 'fa-bold',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-chain',
                    //     'name' => 'fa-chain <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-chain-broken',
                        'name' => 'fa-chain-broken',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-clipboard',
                        'name' => 'fa-clipboard',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-columns',
                        'name' => 'fa-columns',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-copy',
                    //     'name' => 'fa-copy <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    // [
                    //     'class' => 'fa fa-fw fa-cut',
                    //     'name' => 'fa-cut <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-dedent',
                        'name' => 'fa-dedent',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-eraser',
                        'name' => 'fa-eraser',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file',
                        'name' => 'fa-file',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-o',
                        'name' => 'fa-file-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-text',
                        'name' => 'fa-file-text',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-file-text-o',
                        'name' => 'fa-file-text-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-files-o',
                        'name' => 'fa-files-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-floppy-o',
                        'name' => 'fa-floppy-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-font',
                        'name' => 'fa-font',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-header',
                        'name' => 'fa-header',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-indent',
                        'name' => 'fa-indent',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-italic',
                        'name' => 'fa-italic',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-link',
                        'name' => 'fa-link',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-list',
                        'name' => 'fa-list',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-list-alt',
                        'name' => 'fa-list-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-list-ol',
                        'name' => 'fa-list-ol',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-list-ul',
                        'name' => 'fa-list-ul',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-outdent',
                        'name' => 'fa-outdent',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paperclip',
                        'name' => 'fa-paperclip',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paragraph',
                        'name' => 'fa-paragraph',
                        'uses' => [

                        ],
                    ],
                    // [
                    //     'class' => 'fa fa-fw fa-paste',
                    //     'name' => 'fa-paste <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-repeat',
                        'name' => 'fa-repeat',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-rotate-left',
                        'name' => 'fa-rotate-left',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-rotate-right',
                        'name' => 'fa-rotate-right',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    // [
                    //     'class' => 'fa fa-fw fa-save',
                    //     'name' => 'fa-save <span class="text-muted">(alias)</span>',
                        'uses' => [

                        ],
                    // ],
                    
                    [
                        'class' => 'fa fa-fw fa-scissors',
                        'name' => 'fa-scissors',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-strikethrough',
                        'name' => 'fa-strikethrough',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-subscript',
                        'name' => 'fa-subscript',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-superscript',
                        'name' => 'fa-superscript',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-table',
                        'name' => 'fa-table',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-text-height',
                        'name' => 'fa-text-height',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-text-width',
                        'name' => 'fa-text-width',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-th',
                        'name' => 'fa-th',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-th-large',
                        'name' => 'fa-th-large',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-th-list',
                        'name' => 'fa-th-list',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-underline',
                        'name' => 'fa-underline',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-undo',
                        'name' => 'fa-undo',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-unlink',
                        'name' => 'fa-unlink',
                        'uses' => [

                        ],
                    ],
                     // Alias
                  
            //     ];
            // }

                // <section id="directional">
                  
                
        // public function icons('Directional Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-angle-double-down',
                        'name' => 'fa-angle-double-down',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-angle-double-left',
                        'name' => 'fa-angle-double-left',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-angle-double-right',
                        'name' => 'fa-angle-double-right',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-angle-double-up',
                        'name' => 'fa-angle-double-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-angle-down',
                        'name' => 'fa-angle-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-angle-left',
                        'name' => 'fa-angle-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-angle-right',
                        'name' => 'fa-angle-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-angle-up',
                        'name' => 'fa-angle-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-down',
                        'name' => 'fa-arrow-circle-down',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-left',
                        'name' => 'fa-arrow-circle-left',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-o-down',
                        'name' => 'fa-arrow-circle-o-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-o-left',
                        'name' => 'fa-arrow-circle-o-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-o-right',
                        'name' => 'fa-arrow-circle-o-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-o-up',
                        'name' => 'fa-arrow-circle-o-up',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-right',
                        'name' => 'fa-arrow-circle-right',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-arrow-circle-up',
                        'name' => 'fa-arrow-circle-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-down',
                        'name' => 'fa-arrow-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-left',
                        'name' => 'fa-arrow-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-right',
                        'name' => 'fa-arrow-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrow-up',
                        'name' => 'fa-arrow-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrows',
                        'name' => 'fa-arrows',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-arrows-alt',
                        'name' => 'fa-arrows-alt',
                        'uses' => [

                        ],
                    ],
                    // @todo Não funcionando (Em Brando)
                    // [
                    //     'class' => 'fa fa-fw fa-arrows-h',
                    //     'name' => 'fa-arrows-h',
                        'uses' => [

                        ],
                    // ],
                    [
                        'class' => 'fa fa-fw fa-arrows-v',
                        'name' => 'fa-arrows-v',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-caret-down',
                        'name' => 'fa-caret-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-caret-left',
                        'name' => 'fa-caret-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-caret-right',
                        'name' => 'fa-caret-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-caret-square-o-down',
                        'name' => 'fa-caret-square-o-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-caret-square-o-left',
                        'name' => 'fa-caret-square-o-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-caret-square-o-right',
                        'name' => 'fa-caret-square-o-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-caret-square-o-up',
                        'name' => 'fa-caret-square-o-up',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-caret-up',
                        'name' => 'fa-caret-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chevron-circle-down',
                        'name' => 'fa-chevron-circle-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chevron-circle-left',
                        'name' => 'fa-chevron-circle-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chevron-circle-right',
                        'name' => 'fa-chevron-circle-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chevron-circle-up',
                        'name' => 'fa-chevron-circle-up',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-chevron-down',
                        'name' => 'fa-chevron-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chevron-left',
                        'name' => 'fa-chevron-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chevron-right',
                        'name' => 'fa-chevron-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chevron-up',
                        'name' => 'fa-chevron-up',
                        'uses' => [

                        ],
                    ],
                    // @todo Não funcionando (Em Brando)
                    // [
                    //     'class' => 'fa fa-fw fa-exchange',
                    //     'name' => 'fa-exchange',
                        'uses' => [

                        ],
                    // ],
                    [
                        'class' => 'fa fa-fw fa-hand-o-down',
                        'name' => 'fa-hand-o-down',
                        'uses' => [

                        ],
                    ],
                    // @todo Não funcionando (Em Brando)
                    // [
                    //     'class' => 'fa fa-fw fa-hand-o-left',
                    //     'name' => 'fa-hand-o-left',
                        'uses' => [

                        ],
                    // ],
                    [
                        'class' => 'fa fa-fw fa-hand-o-right',
                        'name' => 'fa-hand-o-right',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hand-o-up',
                        'name' => 'fa-hand-o-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-long-arrow-down',
                        'name' => 'fa-long-arrow-down',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-long-arrow-left',
                        'name' => 'fa-long-arrow-left',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-long-arrow-right',
                        'name' => 'fa-long-arrow-right',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-long-arrow-up',
                        'name' => 'fa-long-arrow-up',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-toggle-down',
                        'name' => 'fa-toggle-down',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-toggle-left',
                        'name' => 'fa-toggle-left',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-toggle-right',
                        'name' => 'fa-toggle-right',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-toggle-up',
                        'name' => 'fa-toggle-up',
                        'uses' => [

                        ],
                    ],
                     // Alias
                  
            //     ];
            // }

                // <section id="video-player">
                  
                
        // public function icons('Video Player Icons') {

                //   return [
                    [
                        'class' => 'fa fa-fw fa-arrows-alt',
                        'name' => 'fa-arrows-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-backward',
                        'name' => 'fa-backward',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-compress',
                        'name' => 'fa-compress',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-eject',
                        'name' => 'fa-eject',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-expand',
                        'name' => 'fa-expand',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fast-backward',
                        'name' => 'fa-fast-backward',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fast-forward',
                        'name' => 'fa-fast-forward',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-forward',
                        'name' => 'fa-forward',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pause',
                        'name' => 'fa-pause',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-play',
                        'name' => 'fa-play',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-play-circle',
                        'name' => 'fa-play-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-play-circle-o',
                        'name' => 'fa-play-circle-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-random',
                        'name' => 'fa-random',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-step-backward',
                        'name' => 'fa-step-backward',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-step-forward',
                        'name' => 'fa-step-forward',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-stop',
                        'name' => 'fa-stop',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-youtube-play',
                        'name' => 'fa-youtube-play',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                
                  
                
        // public function icons('Brand Icons') {

                  /** All brand icons are trademarks of their respective owners.</li>
                    *  <li>The use of these trademarks does not indicate endorsement of the trademark holder by Font
                    *    Awesome, nor vice versa.
                    */

                //   return [
                    [
                        'class' => 'fa fa-fw fa-500px',
                        'name' => 'fa-500px',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-adn',
                        'name' => 'fa-adn',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-amazon',
                        'name' => 'fa-amazon',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-android',
                        'name' => 'fa-android',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-angellist',
                        'name' => 'fa-angellist',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-apple',
                        'name' => 'fa-apple',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-behance',
                        'name' => 'fa-behance',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-behance-square',
                        'name' => 'fa-behance-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bitbucket',
                        'name' => 'fa-bitbucket',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-bitbucket-square',
                        'name' => 'fa-bitbucket-square',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-bitcoin',
                        'name' => 'fa-bitcoin',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-black-tie',
                        'name' => 'fa-black-tie',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-btc',
                        'name' => 'fa-btc',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-buysellads',
                        'name' => 'fa-buysellads',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-amex',
                        'name' => 'fa-cc-amex',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-diners-club',
                        'name' => 'fa-cc-diners-club',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-discover',
                        'name' => 'fa-cc-discover',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-jcb',
                        'name' => 'fa-cc-jcb',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-mastercard',
                        'name' => 'fa-cc-mastercard',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-paypal',
                        'name' => 'fa-cc-paypal',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-stripe',
                        'name' => 'fa-cc-stripe',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-cc-visa',
                        'name' => 'fa-cc-visa',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-chrome',
                        'name' => 'fa-chrome',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-codepen',
                        'name' => 'fa-codepen',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-connectdevelop',
                        'name' => 'fa-connectdevelop',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-contao',
                        'name' => 'fa-contao',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-css3',
                        'name' => 'fa-css3',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-dashcube',
                        'name' => 'fa-dashcube',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-delicious',
                        'name' => 'fa-delicious',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-deviantart',
                        'name' => 'fa-deviantart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-digg',
                        'name' => 'fa-digg',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-dribbble',
                        'name' => 'fa-dribbble',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-dropbox',
                        'name' => 'fa-dropbox',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-drupal',
                        'name' => 'fa-drupal',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-empire',
                        'name' => 'fa-empire',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-expeditedssl',
                        'name' => 'fa-expeditedssl',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-facebook',
                        'name' => 'fa-facebook',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-facebook-f',
                        'name' => 'fa-facebook-f',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-facebook-official',
                        'name' => 'fa-facebook-official',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-facebook-square',
                        'name' => 'fa-facebook-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-firefox',
                        'name' => 'fa-firefox',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-flickr',
                        'name' => 'fa-flickr',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-fonticons',
                        'name' => 'fa-fonticons',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-forumbee',
                        'name' => 'fa-forumbee',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-foursquare',
                        'name' => 'fa-foursquare',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ge',
                        'name' => 'fa-ge',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-get-pocket',
                        'name' => 'fa-get-pocket',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gg',
                        'name' => 'fa-gg',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gg-circle',
                        'name' => 'fa-gg-circle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-git',
                        'name' => 'fa-git',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-git-square',
                        'name' => 'fa-git-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-github',
                        'name' => 'fa-github',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-github-alt',
                        'name' => 'fa-github-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-github-square',
                        'name' => 'fa-github-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gittip',
                        'name' => 'fa-gittip',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-google',
                        'name' => 'fa-google',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-google-plus',
                        'name' => 'fa-google-plus',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-google-plus-square',
                        'name' => 'fa-google-plus-square',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-google-wallet',
                        'name' => 'fa-google-wallet',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-gratipay',
                        'name' => 'fa-gratipay',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hacker-news',
                        'name' => 'fa-hacker-news',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-houzz',
                        'name' => 'fa-houzz',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-html5',
                        'name' => 'fa-html5',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-instagram',
                        'name' => 'fa-instagram',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-internet-explorer',
                        'name' => 'fa-internet-explorer',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-ioxhost',
                        'name' => 'fa-ioxhost',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-joomla',
                        'name' => 'fa-joomla',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-jsfiddle',
                        'name' => 'fa-jsfiddle',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-lastfm',
                        'name' => 'fa-lastfm',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-lastfm-square',
                        'name' => 'fa-lastfm-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-leanpub',
                        'name' => 'fa-leanpub',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-linkedin',
                        'name' => 'fa-linkedin',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-linkedin-square',
                        'name' => 'fa-linkedin-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-linux',
                        'name' => 'fa-linux',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-maxcdn',
                        'name' => 'fa-maxcdn',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-meanpath',
                        'name' => 'fa-meanpath',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-medium',
                        'name' => 'fa-medium',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-odnoklassniki',
                        'name' => 'fa-odnoklassniki',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-odnoklassniki-square',
                        'name' => 'fa-odnoklassniki-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-opencart',
                        'name' => 'fa-opencart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-openid',
                        'name' => 'fa-openid',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-opera',
                        'name' => 'fa-opera',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-optin-monster',
                        'name' => 'fa-optin-monster',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pagelines',
                        'name' => 'fa-pagelines',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-paypal',
                        'name' => 'fa-paypal',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pied-piper',
                        'name' => 'fa-pied-piper',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pied-piper-alt',
                        'name' => 'fa-pied-piper-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pinterest',
                        'name' => 'fa-pinterest',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pinterest-p',
                        'name' => 'fa-pinterest-p',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-pinterest-square',
                        'name' => 'fa-pinterest-square',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-qq',
                        'name' => 'fa-qq',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-ra',
                        'name' => 'fa-ra',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-rebel',
                        'name' => 'fa-rebel',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-reddit',
                        'name' => 'fa-reddit',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-reddit-square',
                        'name' => 'fa-reddit-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-renren',
                        'name' => 'fa-renren',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-safari',
                        'name' => 'fa-safari',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-sellsy',
                        'name' => 'fa-sellsy',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-share-alt',
                        'name' => 'fa-share-alt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-share-alt-square',
                        'name' => 'fa-share-alt-square',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-shirtsinbulk',
                        'name' => 'fa-shirtsinbulk',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-simplybuilt',
                        'name' => 'fa-simplybuilt',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-skyatlas',
                        'name' => 'fa-skyatlas',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-skype',
                        'name' => 'fa-skype',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-slack',
                        'name' => 'fa-slack',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-slideshare',
                        'name' => 'fa-slideshare',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-soundcloud',
                        'name' => 'fa-soundcloud',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-spotify',
                        'name' => 'fa-spotify',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-stack-exchange',
                        'name' => 'fa-stack-exchange',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-stack-overflow',
                        'name' => 'fa-stack-overflow',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-steam',
                        'name' => 'fa-steam',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-steam-square',
                        'name' => 'fa-steam-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-stumbleupon',
                        'name' => 'fa-stumbleupon',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-stumbleupon-circle',
                        'name' => 'fa-stumbleupon-circle',
                        'uses' => [

                        ],
                    ],
                    
                    [
                        'class' => 'fa fa-fw fa-tencent-weibo',
                        'name' => 'fa-tencent-weibo',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-trello',
                        'name' => 'fa-trello',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tripadvisor',
                        'name' => 'fa-tripadvisor',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tumblr',
                        'name' => 'fa-tumblr',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-tumblr-square',
                        'name' => 'fa-tumblr-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-twitch',
                        'name' => 'fa-twitch',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-twitter',
                        'name' => 'fa-twitter',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-twitter-square',
                        'name' => 'fa-twitter-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-viacoin',
                        'name' => 'fa-viacoin',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-vimeo',
                        'name' => 'fa-vimeo',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-vimeo-square',
                        'name' => 'fa-vimeo-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-vine',
                        'name' => 'fa-vine',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-vk',
                        'name' => 'fa-vk',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wechat',
                        'name' => 'fa-wechat',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-weibo',
                        'name' => 'fa-weibo',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-weixin',
                        'name' => 'fa-weixin',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-whatsapp',
                        'name' => 'fa-whatsapp',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wikipedia-w',
                        'name' => 'fa-wikipedia-w',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-windows',
                        'name' => 'fa-windows',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wordpress',
                        'name' => 'fa-wordpress',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-xing',
                        'name' => 'fa-xing',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-xing-square',
                        'name' => 'fa-xing-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-y-combinator',
                        'name' => 'fa-y-combinator',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-y-combinator-square',
                        'name' => 'fa-y-combinator-square// Alias',
                        'uses' => [

                        ],
                    [],
                        'class' => 'fa fa-fw fa-yahoo',
                        'name' => 'fa-yahoo',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-yc',
                        'name' => 'fa-yc',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-yc-square',
                        'name' => 'fa-yc-square',
                        'uses' => [

                        ],
                    ],
                     // Alias
                    [
                        'class' => 'fa fa-fw fa-yelp',
                        'name' => 'fa-yelp',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-youtube',
                        'name' => 'fa-youtube',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-youtube-play',
                        'name' => 'fa-youtube-play',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-youtube-square',
                        'name' => 'fa-youtube-square',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }

                // <section id="medical">
                  
                
        // public function icons('Medical Icons') {

            // return [
                    [
                        'class' => 'fa fa-fw fa-ambulance',
                        'name' => 'fa-ambulance',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-h-square',
                        'name' => 'fa-h-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-heart',
                        'name' => 'fa-heart',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-heart-o',
                        'name' => 'fa-heart-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-heartbeat',
                        'name' => 'fa-heartbeat',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-hospital-o',
                        'name' => 'fa-hospital-o',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-medkit',
                        'name' => 'fa-medkit',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-plus-square',
                        'name' => 'fa-plus-square',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-stethoscope',
                        'name' => 'fa-stethoscope',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-user-md',
                        'name' => 'fa-user-md',
                        'uses' => [

                        ],
                    ],
                    [
                        'class' => 'fa fa-fw fa-wheelchair',
                        'name' => 'fa-wheelchair',
                        'uses' => [

                        ],
                    ],
                  
            //     ];
            // }
            // public function icons('glyphicons') {
    
              
            //   <!-- /#fa-icons -->

            //   <!-- glyphicons-->
            //   <div class="tab-pane" id="glyphicons">

            //     <ul, class="bs-glyphicons">
                  [
                    'class' => 'glyphicon glyphicon-asterisk',
                    'name' => 'glyphicon glyphicon-asterisk',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-plus',
                    'name' => 'glyphicon glyphicon-plus',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-euro',
                    'name' => 'glyphicon glyphicon-euro',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-eur',
                    'name' => 'glyphicon glyphicon-eur',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-minus',
                    'name' => 'glyphicon glyphicon-minus',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-cloud',
                    'name' => 'glyphicon glyphicon-cloud',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-envelope',
                    'name' => 'glyphicon glyphicon-envelope',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-pencil',
                    'name' => 'glyphicon glyphicon-pencil',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-glass',
                    'name' => 'glyphicon glyphicon-glass',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-music',
                    'name' => 'glyphicon glyphicon-music',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-search',
                    'name' => 'glyphicon glyphicon-search',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-heart',
                    'name' => 'glyphicon glyphicon-heart',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-star',
                    'name' => 'glyphicon glyphicon-star',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-star-empty',
                    'name' => 'glyphicon glyphicon-star-empty',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-user',
                    'name' => 'glyphicon glyphicon-user',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-film',
                    'name' => 'glyphicon glyphicon-film',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-th-large',
                    'name' => 'glyphicon glyphicon-th-large',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-th',
                    'name' => 'glyphicon glyphicon-th',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-th-list',
                    'name' => 'glyphicon glyphicon-th-list',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-ok',
                    'name' => 'glyphicon glyphicon-ok',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-remove',
                    'name' => 'glyphicon glyphicon-remove',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-zoom-in',
                    'name' => 'glyphicon glyphicon-zoom-in',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-zoom-out',
                    'name' => 'glyphicon glyphicon-zoom-out',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-off',
                    'name' => 'glyphicon glyphicon-off',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-signal',
                    'name' => 'glyphicon glyphicon-signal',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-cog',
                    'name' => 'glyphicon glyphicon-cog',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-trash',
                    'name' => 'glyphicon glyphicon-trash',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-home',
                    'name' => 'glyphicon glyphicon-home',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-file',
                    'name' => 'glyphicon glyphicon-file',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-time',
                    'name' => 'glyphicon glyphicon-time',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-road',
                    'name' => 'glyphicon glyphicon-road',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-download-alt',
                    'name' => 'glyphicon glyphicon-download-alt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-download',
                    'name' => 'glyphicon glyphicon-download',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-upload',
                    'name' => 'glyphicon glyphicon-upload',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-inbox',
                    'name' => 'glyphicon glyphicon-inbox',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-play-circle',
                    'name' => 'glyphicon glyphicon-play-circle',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-repeat',
                    'name' => 'glyphicon glyphicon-repeat',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-refresh',
                    'name' => 'glyphicon glyphicon-refresh',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-list-alt',
                    'name' => 'glyphicon glyphicon-list-alt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-lock',
                    'name' => 'glyphicon glyphicon-lock',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-flag',
                    'name' => 'glyphicon glyphicon-flag',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-headphones',
                    'name' => 'glyphicon glyphicon-headphones',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-volume-off',
                    'name' => 'glyphicon glyphicon-volume-off',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-volume-down',
                    'name' => 'glyphicon glyphicon-volume-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-volume-up',
                    'name' => 'glyphicon glyphicon-volume-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-qrcode',
                    'name' => 'glyphicon glyphicon-qrcode',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-barcode',
                    'name' => 'glyphicon glyphicon-barcode',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tag',
                    'name' => 'glyphicon glyphicon-tag',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tags',
                    'name' => 'glyphicon glyphicon-tags',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-book',
                    'name' => 'glyphicon glyphicon-book',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-bookmark',
                    'name' => 'glyphicon glyphicon-bookmark',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-print',
                    'name' => 'glyphicon glyphicon-print',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-camera',
                    'name' => 'glyphicon glyphicon-camera',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-font',
                    'name' => 'glyphicon glyphicon-font',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-bold',
                    'name' => 'glyphicon glyphicon-bold',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-italic',
                    'name' => 'glyphicon glyphicon-italic',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-text-height',
                    'name' => 'glyphicon glyphicon-text-height',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-text-width',
                    'name' => 'glyphicon glyphicon-text-width',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-align-left',
                    'name' => 'glyphicon glyphicon-align-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-align-center',
                    'name' => 'glyphicon glyphicon-align-center',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-align-right',
                    'name' => 'glyphicon glyphicon-align-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-align-justify',
                    'name' => 'glyphicon glyphicon-align-justify',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-list',
                    'name' => 'glyphicon glyphicon-list',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-indent-left',
                    'name' => 'glyphicon glyphicon-indent-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-indent-right',
                    'name' => 'glyphicon glyphicon-indent-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-facetime-video',
                    'name' => 'glyphicon glyphicon-facetime-video',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-picture',
                    'name' => 'glyphicon glyphicon-picture',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-map-marker',
                    'name' => 'glyphicon glyphicon-map-marker',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-adjust',
                    'name' => 'glyphicon glyphicon-adjust',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tint',
                    'name' => 'glyphicon glyphicon-tint',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-edit',
                    'name' => 'glyphicon glyphicon-edit',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-share',
                    'name' => 'glyphicon glyphicon-share',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-check',
                    'name' => 'glyphicon glyphicon-check',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-move',
                    'name' => 'glyphicon glyphicon-move',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-step-backward',
                    'name' => 'glyphicon glyphicon-step-backward',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-fast-backward',
                    'name' => 'glyphicon glyphicon-fast-backward',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-backward',
                    'name' => 'glyphicon glyphicon-backward',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-play',
                    'name' => 'glyphicon glyphicon-play',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-pause',
                    'name' => 'glyphicon glyphicon-pause',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-stop',
                    'name' => 'glyphicon glyphicon-stop',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-forward',
                    'name' => 'glyphicon glyphicon-forward',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-fast-forward',
                    'name' => 'glyphicon glyphicon-fast-forward',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-step-forward',
                    'name' => 'glyphicon glyphicon-step-forward',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-eject',
                    'name' => 'glyphicon glyphicon-eject',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-chevron-left',
                    'name' => 'glyphicon glyphicon-chevron-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-chevron-right',
                    'name' => 'glyphicon glyphicon-chevron-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-plus-sign',
                    'name' => 'glyphicon glyphicon-plus-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-minus-sign',
                    'name' => 'glyphicon glyphicon-minus-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-remove-sign',
                    'name' => 'glyphicon glyphicon-remove-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-ok-sign',
                    'name' => 'glyphicon glyphicon-ok-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-question-sign',
                    'name' => 'glyphicon glyphicon-question-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-info-sign',
                    'name' => 'glyphicon glyphicon-info-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-screenshot',
                    'name' => 'glyphicon glyphicon-screenshot',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-remove-circle',
                    'name' => 'glyphicon glyphicon-remove-circle',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-ok-circle',
                    'name' => 'glyphicon glyphicon-ok-circle',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-ban-circle',
                    'name' => 'glyphicon glyphicon-ban-circle',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-arrow-left',
                    'name' => 'glyphicon glyphicon-arrow-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-arrow-right',
                    'name' => 'glyphicon glyphicon-arrow-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-arrow-up',
                    'name' => 'glyphicon glyphicon-arrow-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-arrow-down',
                    'name' => 'glyphicon glyphicon-arrow-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-share-alt',
                    'name' => 'glyphicon glyphicon-share-alt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-resize-full',
                    'name' => 'glyphicon glyphicon-resize-full',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-resize-small',
                    'name' => 'glyphicon glyphicon-resize-small',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-exclamation-sign',
                    'name' => 'glyphicon glyphicon-exclamation-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-gift',
                    'name' => 'glyphicon glyphicon-gift',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-leaf',
                    'name' => 'glyphicon glyphicon-leaf',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-fire',
                    'name' => 'glyphicon glyphicon-fire',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-eye-open',
                    'name' => 'glyphicon glyphicon-eye-open',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-eye-close',
                    'name' => 'glyphicon glyphicon-eye-close',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-warning-sign',
                    'name' => 'glyphicon glyphicon-warning-sign',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-plane',
                    'name' => 'glyphicon glyphicon-plane',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-calendar',
                    'name' => 'glyphicon glyphicon-calendar',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-random',
                    'name' => 'glyphicon glyphicon-random',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-comment',
                    'name' => 'glyphicon glyphicon-comment',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-magnet',
                    'name' => 'glyphicon glyphicon-magnet',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-chevron-up',
                    'name' => 'glyphicon glyphicon-chevron-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-chevron-down',
                    'name' => 'glyphicon glyphicon-chevron-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-retweet',
                    'name' => 'glyphicon glyphicon-retweet',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-shopping-cart',
                    'name' => 'glyphicon glyphicon-shopping-cart',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-folder-close',
                    'name' => 'glyphicon glyphicon-folder-close',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-folder-open',
                    'name' => 'glyphicon glyphicon-folder-open',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-resize-vertical',
                    'name' => 'glyphicon glyphicon-resize-vertical',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-resize-horizontal',
                    'name' => 'glyphicon glyphicon-resize-horizontal',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-hdd',
                    'name' => 'glyphicon glyphicon-hdd',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-bullhorn',
                    'name' => 'glyphicon glyphicon-bullhorn',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-bell',
                    'name' => 'glyphicon glyphicon-bell',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-certificate',
                    'name' => 'glyphicon glyphicon-certificate',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-thumbs-up',
                    'name' => 'glyphicon glyphicon-thumbs-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-thumbs-down',
                    'name' => 'glyphicon glyphicon-thumbs-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-hand-right',
                    'name' => 'glyphicon glyphicon-hand-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-hand-left',
                    'name' => 'glyphicon glyphicon-hand-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-hand-up',
                    'name' => 'glyphicon glyphicon-hand-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-hand-down',
                    'name' => 'glyphicon glyphicon-hand-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-circle-arrow-right',
                    'name' => 'glyphicon glyphicon-circle-arrow-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-circle-arrow-left',
                    'name' => 'glyphicon glyphicon-circle-arrow-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-circle-arrow-up',
                    'name' => 'glyphicon glyphicon-circle-arrow-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-circle-arrow-down',
                    'name' => 'glyphicon glyphicon-circle-arrow-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-globe',
                    'name' => 'glyphicon glyphicon-globe',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-wrench',
                    'name' => 'glyphicon glyphicon-wrench',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tasks',
                    'name' => 'glyphicon glyphicon-tasks',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-filter',
                    'name' => 'glyphicon glyphicon-filter',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-briefcase',
                    'name' => 'glyphicon glyphicon-briefcase',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-fullscreen',
                    'name' => 'glyphicon glyphicon-fullscreen',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-dashboard',
                    'name' => 'glyphicon glyphicon-dashboard',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-paperclip',
                    'name' => 'glyphicon glyphicon-paperclip',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-heart-empty',
                    'name' => 'glyphicon glyphicon-heart-empty',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-link',
                    'name' => 'glyphicon glyphicon-link',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-phone',
                    'name' => 'glyphicon glyphicon-phone',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-pushpin',
                    'name' => 'glyphicon glyphicon-pushpin',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-usd',
                    'name' => 'glyphicon glyphicon-usd',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-gbp',
                    'name' => 'glyphicon glyphicon-gbp',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sort',
                    'name' => 'glyphicon glyphicon-sort',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sort-by-alphabet',
                    'name' => 'glyphicon glyphicon-sort-by-alphabet',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sort-by-alphabet-alt',
                    'name' => 'glyphicon glyphicon-sort-by-alphabet-alt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sort-by-order',
                    'name' => 'glyphicon glyphicon-sort-by-order',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sort-by-order-alt',
                    'name' => 'glyphicon glyphicon-sort-by-order-alt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sort-by-attributes',
                    'name' => 'glyphicon glyphicon-sort-by-attributes',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sort-by-attributes-alt',
                    'name' => 'glyphicon glyphicon-sort-by-attributes-alt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-unchecked',
                    'name' => 'glyphicon glyphicon-unchecked',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-expand',
                    'name' => 'glyphicon glyphicon-expand',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-collapse-down',
                    'name' => 'glyphicon glyphicon-collapse-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-collapse-up',
                    'name' => 'glyphicon glyphicon-collapse-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-log-in',
                    'name' => 'glyphicon glyphicon-log-in',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-flash',
                    'name' => 'glyphicon glyphicon-flash',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-log-out',
                    'name' => 'glyphicon glyphicon-log-out',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-new-window',
                    'name' => 'glyphicon glyphicon-new-window',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-record',
                    'name' => 'glyphicon glyphicon-record',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-save',
                    'name' => 'glyphicon glyphicon-save',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-open',
                    'name' => 'glyphicon glyphicon-open',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-saved',
                    'name' => 'glyphicon glyphicon-saved',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-import',
                    'name' => 'glyphicon glyphicon-import',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-export',
                    'name' => 'glyphicon glyphicon-export',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-send',
                    'name' => 'glyphicon glyphicon-send',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-floppy-disk',
                    'name' => 'glyphicon glyphicon-floppy-disk',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-floppy-saved',
                    'name' => 'glyphicon glyphicon-floppy-saved',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-floppy-remove',
                    'name' => 'glyphicon glyphicon-floppy-remove',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-floppy-save',
                    'name' => 'glyphicon glyphicon-floppy-save',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-floppy-open',
                    'name' => 'glyphicon glyphicon-floppy-open',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-credit-card',
                    'name' => 'glyphicon glyphicon-credit-card',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-transfer',
                    'name' => 'glyphicon glyphicon-transfer',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-cutlery',
                    'name' => 'glyphicon glyphicon-cutlery',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-header',
                    'name' => 'glyphicon glyphicon-header',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-compressed',
                    'name' => 'glyphicon glyphicon-compressed',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-earphone',
                    'name' => 'glyphicon glyphicon-earphone',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-phone-alt',
                    'name' => 'glyphicon glyphicon-phone-alt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tower',
                    'name' => 'glyphicon glyphicon-tower',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-stats',
                    'name' => 'glyphicon glyphicon-stats',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sd-video',
                    'name' => 'glyphicon glyphicon-sd-video',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-hd-video',
                    'name' => 'glyphicon glyphicon-hd-video',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-subtitles',
                    'name' => 'glyphicon glyphicon-subtitles',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sound-stereo',
                    'name' => 'glyphicon glyphicon-sound-stereo',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sound-dolby',
                    'name' => 'glyphicon glyphicon-sound-dolby',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sound-5-1',
                    'name' => 'glyphicon glyphicon-sound-5-1',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sound-6-1',
                    'name' => 'glyphicon glyphicon-sound-6-1',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sound-7-1',
                    'name' => 'glyphicon glyphicon-sound-7-1',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-copyright-mark',
                    'name' => 'glyphicon glyphicon-copyright-mark',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-registration-mark',
                    'name' => 'glyphicon glyphicon-registration-mark',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-cloud-download',
                    'name' => 'glyphicon glyphicon-cloud-download',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-cloud-upload',
                    'name' => 'glyphicon glyphicon-cloud-upload',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tree-conifer',
                    'name' => 'glyphicon glyphicon-tree-conifer',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tree-deciduous',
                    'name' => 'glyphicon glyphicon-tree-deciduous',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-cd',
                    'name' => 'glyphicon glyphicon-cd',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-save-file',
                    'name' => 'glyphicon glyphicon-save-file',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-open-file',
                    'name' => 'glyphicon glyphicon-open-file',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-level-up',
                    'name' => 'glyphicon glyphicon-level-up',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-copy',
                    'name' => 'glyphicon glyphicon-copy',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-paste',
                    'name' => 'glyphicon glyphicon-paste',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-alert',
                    'name' => 'glyphicon glyphicon-alert',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-equalizer',
                    'name' => 'glyphicon glyphicon-equalizer',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-king',
                    'name' => 'glyphicon glyphicon-king',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-queen',
                    'name' => 'glyphicon glyphicon-queen',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-pawn',
                    'name' => 'glyphicon glyphicon-pawn',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-bishop',
                    'name' => 'glyphicon glyphicon-bishop',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-knight',
                    'name' => 'glyphicon glyphicon-knight',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-baby-formula',
                    'name' => 'glyphicon glyphicon-baby-formula',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-tent',
                    'name' => 'glyphicon glyphicon-tent',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-blackboard',
                    'name' => 'glyphicon glyphicon-blackboard',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-bed',
                    'name' => 'glyphicon glyphicon-bed',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-apple',
                    'name' => 'glyphicon glyphicon-apple',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-erase',
                    'name' => 'glyphicon glyphicon-erase',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-hourglass',
                    'name' => 'glyphicon glyphicon-hourglass',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-lamp',
                    'name' => 'glyphicon glyphicon-lamp',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-duplicate',
                    'name' => 'glyphicon glyphicon-duplicate',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-piggy-bank',
                    'name' => 'glyphicon glyphicon-piggy-bank',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-scissors',
                    'name' => 'glyphicon glyphicon-scissors',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-bitcoin',
                    'name' => 'glyphicon glyphicon-bitcoin',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-btc',
                    'name' => 'glyphicon glyphicon-btc',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-xbt',
                    'name' => 'glyphicon glyphicon-xbt',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-yen',
                    'name' => 'glyphicon glyphicon-yen',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-jpy',
                    'name' => 'glyphicon glyphicon-jpy',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-ruble',
                    'name' => 'glyphicon glyphicon-ruble',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-rub',
                    'name' => 'glyphicon glyphicon-rub',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-scale',
                    'name' => 'glyphicon glyphicon-scale',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-ice-lolly',
                    'name' => 'glyphicon glyphicon-ice-lolly',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-ice-lolly-tasted',
                    'name' => 'glyphicon glyphicon-ice-lolly-tasted',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-education',
                    'name' => 'glyphicon glyphicon-education',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-option-horizontal',
                    'name' => 'glyphicon glyphicon-option-horizontal',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-option-vertical',
                    'name' => 'glyphicon glyphicon-option-vertical',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-menu-hamburger',
                    'name' => 'glyphicon glyphicon-menu-hamburger',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-modal-window',
                    'name' => 'glyphicon glyphicon-modal-window',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-oil',
                    'name' => 'glyphicon glyphicon-oil',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-grain',
                    'name' => 'glyphicon glyphicon-grain',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-sunglasses',
                    'name' => 'glyphicon glyphicon-sunglasses',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-text-size',
                    'name' => 'glyphicon glyphicon-text-size',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-text-color',
                    'name' => 'glyphicon glyphicon-text-color',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-text-background',
                    'name' => 'glyphicon glyphicon-text-background',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-object-align-top',
                    'name' => 'glyphicon glyphicon-object-align-top',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-object-align-bottom',
                    'name' => 'glyphicon glyphicon-object-align-bottom',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-object-align-horizontal',
                    'name' => 'glyphicon glyphicon-object-align-horizontal',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-object-align-left',
                    'name' => 'glyphicon glyphicon-object-align-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-object-align-vertical',
                    'name' => 'glyphicon glyphicon-object-align-vertical',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-object-align-right',
                    'name' => 'glyphicon glyphicon-object-align-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-triangle-right',
                    'name' => 'glyphicon glyphicon-triangle-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-triangle-left',
                    'name' => 'glyphicon glyphicon-triangle-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-triangle-bottom',
                    'name' => 'glyphicon glyphicon-triangle-bottom',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-triangle-top',
                    'name' => 'glyphicon glyphicon-triangle-top',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-console',
                    'name' => 'glyphicon glyphicon-console',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-superscript',
                    'name' => 'glyphicon glyphicon-superscript',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-subscript',
                    'name' => 'glyphicon glyphicon-subscript',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-menu-left',
                    'name' => 'glyphicon glyphicon-menu-left',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-menu-right',
                    'name' => 'glyphicon glyphicon-menu-right',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-menu-down',
                    'name' => 'glyphicon glyphicon-menu-down',
                        'uses' => [

                        ],
                  ],
                  [
                    'class' => 'glyphicon glyphicon-menu-up',
                    'name' => 'glyphicon glyphicon-menu-up',
                        'uses' => [

                        ],
                  ]
        ];
            //     </ul>
              
            //   <!-- /#ion-icons -->
    }
}
            