<?php

namespace Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt;

use Innoboxrr\LaravelAudit\Models\LoginAttempt;
use Innoboxrr\LaravelAudit\Http\Resources\Models\LoginAttemptResource;
use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\LaravelAudit\Http\Events\LoginAttempt\Events\DeleteEvent;

class DeleteRequest extends FormRequest
{

    public function authorize()
    {
        
        $loginAttempt = LoginAttempt::findOrFail($this->login_attempt_id);

        return $this->user()->can('delete', $loginAttempt);

    }

    public function rules()
    {
        return [
            'login_attempt_id' => 'required|numeric'
        ];
    }

    public function handle()
    {

        $loginAttempt = LoginAttempt::findOrFail($request->login_attempt_id);

        $loginAttempt->deleteModel();

        $response = new LoginAttemptResource($loginAttempt);

        event(new DeleteEvent($loginAttempt, $request, $response));

        return $response;

    }
    
}
