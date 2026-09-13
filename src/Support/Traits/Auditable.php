<?php

namespace Innoboxrr\LaravelAudit\Support\Traits;

use Innoboxrr\LaravelAudit\Models\Audit;
use Innoboxrr\LaravelAudit\Models\Action;

/**
 * Este trait lo debe implementar todo modelo que sea auditable
 **/

trait Auditable 
{

	public function audits()
	{
		return $this->morphMany('Innoboxrr\LaravelAudit\Models\Audit', 'loggable');
	}

	public function log(string $type)
	{

		if (!app()->runningInConsole() && auth()->check()) {
		
			return Audit::create([
		    	'before' => json_encode($this->getOriginal()),
		    	'after' => ($this->isDirty()) ? json_encode($this->getAttributes()) : null,
		        'route' => request()->url(),
		        // Las columnas no admiten null y un cliente sin User-Agent (curl,
		        // un script) hacia fallar la operacion que se estaba auditando.
		        'ip_address' => (string) request()->ip(),
		        'user_agent' => (string) request()->header('User-Agent'),
		        'loggable_id' => $this->id,
		        'loggable_type' => get_class($this),
		        'user_id' => auth()->id(),
		        'action_id' => Action::getId($type, $this),
		    ]);

		}
	    
	}

}