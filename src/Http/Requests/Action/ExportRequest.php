<?php

namespace Innoboxrr\LaravelAudit\Http\Requests\Action;

use Innoboxrr\LaravelAudit\Models\Action;
use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\LaravelAudit\Http\Events\Action\Events\ExportEvent;

class ExportRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        
        $this->merge([
            'paginate' => 0,
            'managed' => true,
            'except_view_any' => true,
        ]);

    }

    public function authorize()
    {
        return $this->user()->can('export', Action::class);
    }

    public function rules()
    {
        return [
            //
        ];
    }

    public function handle()
    {

        // maatwebsite/excel es sugerido, no requerido. Sin el, la notificacion
        // moria mas tarde con una clase inexistente y el SPA solo veia un 500.
        if (! class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return response()->json([
                'status' => false,
                'message' => 'La exportación necesita maatwebsite/excel. Instálalo con: composer require maatwebsite/excel',
            ], 501);
        }

        event(new ExportEvent($this));

        return response()->json(['status' => true]);

    }
    
}
