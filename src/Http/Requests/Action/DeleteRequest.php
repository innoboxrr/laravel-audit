<?php

namespace Innoboxrr\LaravelAudit\Http\Requests\Action;

use Innoboxrr\LaravelAudit\Models\Action;
use Innoboxrr\LaravelAudit\Http\Resources\Models\ActionResource;
use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\LaravelAudit\Http\Events\Action\Events\DeleteEvent;

class DeleteRequest extends FormRequest
{

    public function authorize()
    {
        
        $action = Action::findOrFail($this->action_id);

        return $this->user()->can('delete', $action);

    }

    public function rules()
    {
        return [
            'action_id' => 'required|numeric'
        ];
    }

    public function handle()
    {

        $action = Action::findOrFail($this->action_id);

        $action->deleteModel();

        $response = new ActionResource($action);

        event(new DeleteEvent($action, $this, $response));

        return $response;

    }
    
}
