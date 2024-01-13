<?php

namespace Innoboxrr\LaravelAudit\Http\Controllers;

use Innoboxrr\LaravelAudit\Http\Requests\Audit\PoliciesRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\PolicyRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\IndexRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\ShowRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\CreateRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\UpdateRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\DeleteRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\RestoreRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\ForceDeleteRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Audit\ExportRequest;

class AuditController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function policies(PoliciesRequest $request)
    {
        return $request->handle($this);   
    }

    public function policy(PolicyRequest $request)
    {
        return $request->handle();
    }

    public function index(IndexRequest $request)
    {
        return $request->handle();   
    }

    public function show(ShowRequest $request)
    {
        return $request->handle();   
    }

    public function create(CreateRequest $request)
    {
        return $request->handle();   
    }

    public function update(UpdateRequest $request)
    {
        return $request->handle();   
    }

    public function delete(DeleteRequest $request)
    {
        return $request->handle();   
    }

    public function restore(RestoreRequest $request)
    {
        return $request->handle();   
    }

    public function forceDelete(ForceDeleteRequest $request)
    {
        return $request->handle();   
    }

    public function export(ExportRequest $request)
    {
        return $request->handle();   
    }

}
