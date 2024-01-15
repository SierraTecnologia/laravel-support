<?php

declare(strict_types=1);

namespace Muleta\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug as BaseHasSlug;
use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
    use BaseHasSlug;

    /**
     * Boot the trait.
     */
    protected static function bootHasSlug()
    {
        static::saving(
            function (Model $model) {
                collect($model->getTranslatedLocales('name'))
                ->each(
                    function (string $locale) use ($model) {
                        $model->setTranslation('code', $locale, $model->generateSlug($locale));
                    }
                );
            }
        );
        // // @todo tava dando erro
        // // Auto generate slugs early before validation
        // static::validating(
        //     function (Model $model) {
        //         if ($model->exists && $model->getSlugOptions()->generateSlugsOnUpdate) {
        //             $model->generateSlugOnUpdate();
        //         } elseif (! $model->exists && $model->getSlugOptions()->generateSlugsOnCreate) {
        //             $model->generateSlugOnCreate();
        //         }
        //     }
        // );
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
// <?php

// namespace Muleta\Traits\Models;

// use Illuminate\Database\Eloquent\Model;

// trait HasSlug
// {
//     public static function bootHasSlug()
//     {
//         static::saving(
//             function (Model $model) {
//                 collect($model->getTranslatedLocales('name'))
//                 ->each(
//                     function (string $locale) use ($model) {
//                         $model->setTranslation('code', $locale, $model->generateSlug($locale));
//                     }
//                 );
//             }
//         );
//     }

//     protected function generateSlug(string $locale): string
//     {
//         $slugger = \Illuminate\Support\Facades\Config::get('tags.slugger');

//         $slugger = $slugger ?: '\Illuminate\Support\Str::slug';

//         return call_user_func($slugger, $this->getTranslation('name', $locale));
//     }
// }
