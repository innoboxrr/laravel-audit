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

        $query = $builder->get(Audit::class, $request);

        return AuditResource::collection($query);

    }
}
