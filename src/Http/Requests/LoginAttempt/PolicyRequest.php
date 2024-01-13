<?php

namespace Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt;

use Innoboxrr\LaravelAudit\Models\LoginAttempt;
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
                'exists:Innoboxrr\LaravelAudit\Models\LoginAttempt,id',
                Rule::requiredIf(in_array($this->policy, $this->modelPolicies)),
            ]
        ];
    }

    public function handle()
    {

         $loginAttempt = ($this->id) ? 
            LoginAttempt::findOrFail($this->id) : 
            app(LoginAttempt::class);

        return response()->json([
            $this->policy => user()->can($this->policy, $loginAttempt),
        ]);

    }

}
