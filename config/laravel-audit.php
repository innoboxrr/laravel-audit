<?php

return [

	'db_prefix' => '',

	'user_class' => 'App\Models\User',

	'excel_view' => 'innoboxrrlaravelaudit::excel.',

	'notification_via' => ['mail', 'database'],

	'export_disk' => 's3',

	'builder' => [
		'basePath' => root_path() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'innoboxrr' . DIRECTORY_SEPARATOR . 'laravel-audit' . DIRECTORY_SEPARATOR,
		'filtersPath' => 'src' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Filters',
		'filtersNamespace' => 'Innoboxrr\LaravelAudit\Models\Filters',
	],
	
];