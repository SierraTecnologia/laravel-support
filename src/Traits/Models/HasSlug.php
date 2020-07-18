<?php

declare(strict_types=1);

namespace Support\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug as BaseHasSlug;

trait HasSlug
{
    use BaseHasSlug;

    /**
     * Boot the trait.
     */
    protected static function bootHasSlug()
    {
        // Auto generate slugs early before validation
        static::validating(
            function (Model $model) {
                if ($model->exists && $model->getSlugOptions()->generateSlugsOnUpdate) {
                    $model->generateSlugOnUpdate();
                } elseif (! $model->exists && $model->getSlugOptions()->generateSlugsOnCreate) {
                    $model->generateSlugOnCreate();
                }
            }
        );
    }
}
// <?php

// namespace Support\Traits\Models;

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
