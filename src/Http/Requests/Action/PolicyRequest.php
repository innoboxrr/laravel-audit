<?php

namespace Innoboxrr\LaravelAudit\Http\Requests\Action;

use Innoboxrr\LaravelAudit\Models\Action;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PolicyRequest extends FormRequest
{

    protected $policies = [
        'index',
        'view',
        'viewAny',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
        'export',
    ];

    protected $modelPolicies = [
        'view',
        'update',
        'delete',
        'restore',
        'forceDelete',
    ];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'policy' => [
                'required',
                Rule::in($this->policies)
            ],
            'id' => [
                'numeric',
                'exists:Innoboxrr\LaravelAudit\Models\Action,id',
                Rule::requiredIf(in_array($this->policy, $this->modelPolicies)),
            ]
        ];
    }

    public function handle()
    {

         $action = ($this->id) ? 
            Action::findOrFail($this->id) : 
            app(Action::class);

        return response()->json([
            $this->policy => user()->can($this->policy, $action),
        ]);

    }

}
