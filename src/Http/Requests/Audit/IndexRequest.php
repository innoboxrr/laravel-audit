<?php

namespace Innoboxrr\LaravelAudit\Http\Requests\Audit;

use Innoboxrr\LaravelAudit\Models\Audit;
use Innoboxrr\LaravelAudit\Http\Resources\Models\AuditResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Innoboxrr\SearchSurge\Search\Builder;

class IndexRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('index', Audit::class);
    }

    public function rules()
    {
        return [
            //
        ];
    }

    public function handle()
    {

        $builder = new Builder();

        $builder->setBasePath(config('laravel-audit.builder.basePath'));

        $builder->setOptions([
            'filtersPath' => config('laravel-audit.builder.filtersPath'),
            'filtersNamespace' => config('laravel-audit.builder.filtersNamespace'),
        ]);

        $query = $builder->get(Audit::class, $this->all());

        return AuditResource::collection($query);

    }
}
