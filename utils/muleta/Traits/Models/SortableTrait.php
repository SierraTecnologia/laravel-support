<?php

declare(strict_types=1);

namespace Muleta\Traits\Models;

use ArrayAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use InvalidArgumentException;

trait SortableTrait
{
    public static function bootSortableTrait()
    {
        static::creating(
            function ($model) {
                if ($model instanceof Sortable && $model->shouldSortWhenCreating()) {
                    $model->setHighestOrderNumber();
                }
            }
        );
    }

    public function setHighestOrderNumber()
    {
        $orderColumnName = $this->determineOrderColumnName();

        $this->$orderColumnName = $this->getHighestOrderNumber() + 1;
    }

    public function getHighestOrderNumber(): int
    {
        return (int) $this->buildSortQuery()->max($this->determineOrderColumnName());
    }

    public function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy($this->determineOrderColumnName(), $direction);
    }

    public static function setNewOrder($ids, int $startOrder = 1, string $primaryKeyColumn = null)
    {
        if (!is_array($ids) && !$ids instanceof ArrayAccess) {
            throw new InvalidArgumentException('You must pass an array or ArrayAccess object to setNewOrder');
        }

        $model = new static;

        $orderColumnName = $model->determineOrderColumnName();

        if (is_null($primaryKeyColumn)) {
            $primaryKeyColumn = $model->getKeyName();
        }

        foreach ($ids as $id) {
            static::withoutGlobalScope(SoftDeletingScope::class)
                ->where($primaryKeyColumn, $id)
                ->update([$orderColumnName => $startOrder++]);
        }
    }

    public static function setNewOrderByCustomColumn(string $primaryKeyColumn, $ids, int $startOrder = 1)
    {
        self::setNewOrder($ids, $startOrder, $primaryKeyColumn);
    }

    protected function determineOrderColumnName(): string
    {
        if (
            isset($this->sortable['order_column_name'])
            && !empty($this->sortable['order_column_name'])
        ) {
            return $this->sortable['order_column_name'];
        }

        return 'order_column';
    }

    /**
     * Determine if the order column should be set when saving a new model instance.
     */
    public function shouldSortWhenCreating(): bool
    {
        return $this->sortable['sort_when_creating'] ?? true;
    }

    public function moveOrderDown()
    {
        $orderColumnName = $this->determineOrderColumnName();

        $swapWithModel = $this->buildSortQuery()->limit(1)
            ->ordered()
            ->where($orderColumnName, '>', $this->$orderColumnName)
            ->first();

        if (!$swapWithModel) {
            return $this;
        }

        return $this->swapOrderWithModel($swapWithModel);
    }

    public function moveOrderUp()
    {
        $orderColumnName = $this->determineOrderColumnName();

        $swapWithModel = $this->buildSortQuery()->limit(1)
            ->ordered('desc')
            ->where($orderColumnName, '<', $this->$orderColumnName)
            ->first();

        if (!$swapWithModel) {
            return $this;
        }

        return $this->swapOrderWithModel($swapWithModel);
    }

    public function swapOrderWithModel(Sortable $otherModel)
    {
        $orderColumnName = $this->determineOrderColumnName();

        $oldOrderOfOtherModel = $otherModel->$orderColumnName;

        $otherModel->$orderColumnName = $this->$orderColumnName;
        $otherModel->save();

        $this->$orderColumnName = $oldOrderOfOtherModel;
        $this->save();

        return $this;
    }

    public static function swapOrder(Sortable $model, Sortable $otherModel)
    {
        $model->swapOrderWithModel($otherModel);
    }

    public function moveToStart()
    {
        $firstModel = $this->buildSortQuery()->limit(1)
            ->ordered()
            ->first();

        if ($firstModel->id === $this->id) {
            return $this;
        }

        $orderColumnName = $this->determineOrderColumnName();

        $this->$orderColumnName = $firstModel->$orderColumnName;
        $this->save();

        $this->buildSortQuery()->where($this->getKeyName(), '!=', $this->id)->increment($orderColumnName);

        return $this;
    }

    public function moveToEnd()
    {
        $maxOrder = $this->getHighestOrderNumber();

        $orderColumnName = $this->determineOrderColumnName();

        if ($this->$orderColumnName === $maxOrder) {
            return $this;
        }

        $oldOrder = $this->$orderColumnName;

        $this->$orderColumnName = $maxOrder;
        $this->save();

        $this->buildSortQuery()->where($this->getKeyName(), '!=', $this->id)
            ->where($orderColumnName, '>', $oldOrder)
            ->decrement($orderColumnName);

        return $this;
    }

    public function buildSortQuery()
    {
        return static::query();
    }

    /**
     * ACrescentei
     */
    //---------------------------------------------------------------------------
    // Scopes
    //---------------------------------------------------------------------------

    /**
     * Search the title (where "title" is the admin definiton of the title) for
     * the terms.  This is designed for the Facilitador autocomplete
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @param  string                            $term
     * @throws Facilitador\Exceptions\Exception
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeTitleContains($query, $term, $exact = false)
    {
        // Get an instance so the title attributes can be found.
        if (!$model = static::first()) {
            return;
        }

        // Get the title attributes
        $attributes = $model->titleAttributes();
        if (empty($attributes)) {
            throw new Exception('No searchable attributes');
        }

        // Concatenate all the attributes with spaces and look for the term.
        switch (DB::getDriverName()) {
            case 'mysql':
                $source = DB::raw('CONCAT(' . implode('," ",', $attributes) . ')');
                break;
            case 'sqlite':
            case 'pgsql':
                $source = DB::raw(implode(' || ', $attributes));
                break;

                // For SQL Server, only support concatenating of two attributes so
                // it works in 2008 and above.
                // https://stackoverflow.com/a/47423292/59160
            case 'sqlsrv':
                if (count($attributes) == 2) {
                    $source = DB::raw('{fn CONCAT(' . implode(',', $attributes) . ')}');
                } else {
                    $source = $attributes[0];
                }
        }

        return $exact ?
            $query->where($source, '=', $term) :
            $query->where($source, 'LIKE', "%$term%");
    }


    /**
     * Get publically visible items. The scope couldn't be `public` because PHP
     * took issue with it as a function name.
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeIsPublic($query)
    {
        return $query->where($this->getTable() . '.public', '1');
    }

    /**
     * Get all public items by the default order
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOrderedAndPublic($query)
    {
        return $query->ordered()->isPublic();
    }

    /**
     * Get all public items by the default order.  This is a good thing to
     * subclass to define special listing scopes used ONLY on the frontend.  As
     * compared with scopeOrdered().
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeListing($query)
    {
        return $query->orderedAndPublic();
    }

    /**
     * Order a table that has a position value
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopePositioned($query)
    {
        $query->orderBy($this->getTable() . '.position', 'asc');
        if ($this->usesTimestamps()) {
            $query->orderBy($this->getTable() . '.created_at', 'desc');
        }
        return $query;
    }

    /**
     * Get only public records by default for Bkwld\SitemapFromRoute
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeForSitemap($query)
    {
        return $query->isPublic();
    }

    /**
     * Randomize the results in the DB.  This shouldn't be used for large datasets
     * cause it's not very performant
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @param  mixed                             $seed  Providing a seed keeps the order the same on subsequent queries
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeRandomize($query, $seed = false)
    {
        if ($seed === true) {
            $seed = Session::getId();
        }

        if ($seed) {
            return $query->orderBy(DB::raw('RAND("' . $seed . '")'));
        }

        return $query->orderBy(DB::raw('RAND()'));
    }

    /**
     * Get localized siblings of this model
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOtherLocalizations($query)
    {
        return $query->where('locale_group', $this->locale_group)
            ->where($this->getKeyName(), '!=', $this->getKey());
    }
}
