<?php

namespace Innoboxrr\LaravelAudit\Exports;

use Innoboxrr\LaravelAudit\Models\Action;
use Innoboxrr\SearchSurge\Search\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ActionsExports implements FromView
{

    protected $request;

    public function __construct($request) 
    {

        $this->request = $request;

    }

    public function view(): View
    {
        return view(
            config(
                'innoboxrrlaravelaudit.excel_view', 
                'innoboxrrlaravelaudit::excel.'
            ) . 'action', 
            [
                'actions' => $this->getQuery()
            ]
        );
    }

    public function getQuery()
    {   

        $builder = new Builder();

        // search-surge 3 recibe un arreglo; pasarle la peticion era un TypeError.
        return $builder->get(Action::class, is_array($this->request) ? $this->request : $this->request->all());

    }

}