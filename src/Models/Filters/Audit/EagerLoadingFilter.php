<?php

namespace Innoboxrr\LaravelAudit\Models\Filters\Audit;

use Illuminate\Database\Eloquent\Builder;
use Innoboxrr\SearchSurge\Search\Support\DataContainer;
use Innoboxrr\SearchSurge\Search\Utils\Order;

class EagerLoadingFilter
{

    public static function apply(Builder $query, DataContainer $data)
    {

        if ($data->load_loggable == 1 || $data->load_loggable == true) {

            $query->with(['loggable']);

        }

        if ($data->load_user == 1 || $data->load_user == true) {

            $query->with(['user']);

        }

        if ($data->load_action == 1 || $data->load_action == true) {

            $query->with(['action']);

        }

        /*

        if ($data->load_relation == 1 || $data->load_relation == true) {

            $query->with(['relation']);

        }

        */

        return $query;

    }

}
