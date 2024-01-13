<?php

namespace Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt;

use Innoboxrr\LaravelAudit\Models\LoginAttempt;
use Illuminate\Foundation\Http\FormRequest;

class PoliciesRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'nullable|numeric|exists:Innoboxrr\LaravelAudit\Models\LoginAttempt,id'
        ];
    }

    public function handle($controllerClass)
    {

        $model = ($this->id) ? LoginAttempt::findOrFail($this->id) : app(LoginAttempt::class);

        $result = [];

        $methods = get_class_methods($controllerClass);

        $policyMethodMapping = [
            'show' => 'view', // Mapea 'show' a 'view'
            // Agrega aquí más mapeos si es necesario
        ];

        foreach ($methods as $method) {

            $policyMethod = $policyMethodMapping[$method] ?? $method; // Obtiene el nombre de la política correspondiente, o el mismo nombre del método si no hay mapeo

            if (method_exists($controllerClass, $method) && is_callable([$controllerClass, $method])) {
            
                $result[$method] = $this->user()->can($policyMethod, $model);
        
                $result[$policyMethod] = $this->user()->can($policyMethod, $model);
            
            }

        }

        return response()->json($result);

    }
    
}
