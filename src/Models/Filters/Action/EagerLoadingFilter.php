<?php

namespace Innoboxrr\LaravelAudit\Models\Filters\Action;

use Illuminate\Database\Eloquent\Builder;
use Innoboxrr\SearchSurge\Search\Support\DataContainer;
use Innoboxrr\SearchSurge\Search\Utils\Order;

class EagerLoadingFilter
{

    public static function apply(Builder $query, DataContainer $data)
    {

        if ($data->load_audits == 1 || $data->load_audits == true) {

            $query->with(['audits']);

        }

        if ($data->load_actionable == 1 || $data->load_actionable == true) {

            $query->with(['actionable']);

        }
        
        /*

        if ($data->load_relation == 1 || $data->load_relation == true) {

            $query->with(['relation']);

        }

        */

        return $query;

    }

}
