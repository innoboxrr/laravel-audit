<?php

namespace Innoboxrr\LaravelAudit\Models\Traits\Storage;

trait ActionStorage
{

    public function createModel($request)
    {

        $action = $this->create($request->only($this->creatable));

        return $action;

    }

    public function updateModel($request)
    {
     
        $this->update($request->only($this->updatable));

        return $this;

    }

    /*
    public function updateModelMetas($request)
    {

        $this->update_metas($request, 'ActionMeta', 'action_id')->updatePayload();

        return $this;

    }
    */

    public function deleteModel()
    {

        $this->delete();

    }

    public function restoreModel()
    {

        $this->restore();

    }

    public function forceDeleteModel()
    {

        abort(403);

        $this->forceDelete();
        
    }

}