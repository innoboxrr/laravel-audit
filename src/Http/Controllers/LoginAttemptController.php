<?php

namespace Innoboxrr\LaravelAudit\Http\Controllers;

use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\PoliciesRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\PolicyRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\IndexRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\ShowRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\CreateRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\UpdateRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\DeleteRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\RestoreRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\ForceDeleteRequest;
use Innoboxrr\LaravelAudit\Http\Requests\LoginAttempt\ExportRequest;

class LoginAttemptController extends Controller
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
