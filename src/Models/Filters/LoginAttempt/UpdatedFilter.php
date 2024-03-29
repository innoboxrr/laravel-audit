<?php

namespace Innoboxrr\LaravelAudit\Models\Filters\LoginAttempt;

use Illuminate\Database\Eloquent\Builder;
use Innoboxrr\SearchSurge\Search\Support\DataContainer;
use Innoboxrr\SearchSurge\Search\Utils\Order;
use Innoboxrr\SearchSurge\Search\Utils\UpdatedFilterQuery;

class UpdatedFilter
{

    public static function apply(Builder $query, DataContainer $data)
    {

        $query = UpdatedFilterQuery::sort($query, $data);

        $query = Order::orderBy($query, $data, 'updated_at');

        return $query;

    }

}
