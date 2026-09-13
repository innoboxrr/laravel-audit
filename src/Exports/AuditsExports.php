<?php

namespace Innoboxrr\LaravelAudit\Exports;

use Innoboxrr\LaravelAudit\Models\Audit;
use Innoboxrr\SearchSurge\Search\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AuditsExports implements FromView
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
            ) . 'audit', 
            [
                'audits' => $this->getQuery()
            ]
        );
    }

    public function getQuery()
    {   

        $builder = new Builder();

        // search-surge 3 recibe un arreglo; pasarle la peticion era un TypeError.
        return $builder->get(Audit::class, is_array($this->request) ? $this->request : $this->request->all());

    }

}