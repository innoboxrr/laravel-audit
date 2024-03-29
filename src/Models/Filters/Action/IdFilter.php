<?php

namespace Innoboxrr\LaravelAudit\Models\Filters\Action;

use Illuminate\Database\Eloquent\Builder;
use Innoboxrr\SearchSurge\Search\Support\DataContainer;
use Innoboxrr\SearchSurge\Search\Utils\Order;

class IdFilter
{

    public static function apply(Builder $query, DataContainer $data)
    {

        if ($data->id) {

            $query->where('id', $data->id);

        }

        $query = Order::orderBy($query, $data, 'id');

        return $query;

    }

}
