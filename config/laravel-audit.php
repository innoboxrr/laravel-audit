<?php

return [

	'db_prefix' => '',

	'user_class' => 'App\Models\User',

	'excel_view' => 'innoboxrrlaravelaudit::excel.',

	'notification_via' => ['mail', 'database'],

	/*
	 * Disco donde se guardan las exportaciones. Local por defecto: una
	 * aplicacion nueva no tiene S3 configurado y la exportacion fallaba.
	 */
	'export_disk' => env('LARAVEL_AUDIT_EXPORT_DISK', 'local'),

	/*
	 * base_path() y no root_path(): root_path() solo existe en
	 * larapack-generator, una dependencia de desarrollo, y con
	 * `composer install --no-dev` cargar esta config tiraba cada peticion.
	 */
	'builder' => [
		'basePath' => base_path() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'innoboxrr' . DIRECTORY_SEPARATOR . 'laravel-audit' . DIRECTORY_SEPARATOR,
		'filtersPath' => 'src' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Filters',
		'filtersNamespace' => 'Innoboxrr\LaravelAudit\Models\Filters',
	],

];
