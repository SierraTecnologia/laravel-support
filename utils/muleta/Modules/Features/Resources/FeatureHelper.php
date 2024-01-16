<?php

namespace Muleta\Modules\Features\Resources;


class FeatureHelper
{
    public static function allFeatures(): array
    {
        $features = [];

        /**
         * Estatisticas em Tracking
         */
        $features[] = 'writelabel';
        /**
         * Modulo de CMS
         */
        $features[] = 'events';
        $features[] = 'faqs';
        $features[] = 'pages';
        $features[] = 'blog';



        $features[] = 'telefonica';

        
        /**
         * Mensageria
         */
        $features[] = 'transmissor';

        /**
         * Modulo de Acessorios, Skills
         */
        $features[] = 'espolio';

        /**
         * Modulo de Produtos, Planos
         */
        $features[] = 'commerce';

        /**
         * Bancario
         */
        $features[] = 'bancario';
        $features[] = 'tradding';

        /**
         * Integrações
         */
        $features[] = 'integrations';
        $features[] = 'services';

        /**
         * Informate
         */
        $features[] = 'social-relations';
        $features[] = 'social-gostos';


        /**
         * Locais
         */
        $features[] = 'locaravel';
        $features[] = 'locais';
        
        /**
         * Trainning
         */
        $features[] = 'academy';

        return $features;
    }
    public static function hasActiveFeature($feature): bool
    {
        if (is_array($feature)) {
            foreach ($feature as $oneFeature) {
                if (self::hasActiveFeature($oneFeature)) {
                    return true;
                }
            }
            \Log::debug('Feature Desativada: '.implode(',', $feature));
            return false;
        }

        if (config('cms.active-core-features', false) && in_array($feature, config('cms.active-core-features'))) {
            return true;
        }
        if (in_array($feature, config('siravel.active-core-features', []))) {
            return true;
        }
        
        \Log::debug('Feature Desativada: '.$feature);
        return false;
    }

}
