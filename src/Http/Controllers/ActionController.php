<?php

namespace Innoboxrr\LaravelAudit\Http\Controllers;

use Innoboxrr\LaravelAudit\Http\Requests\Action\PoliciesRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\PolicyRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\IndexRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\ShowRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\CreateRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\UpdateRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\DeleteRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\RestoreRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\ForceDeleteRequest;
use Innoboxrr\LaravelAudit\Http\Requests\Action\ExportRequest;

class ActionController extends Controller 
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
