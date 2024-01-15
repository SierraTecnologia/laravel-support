<?php namespace Muleta\Library;

// Dependencies
use Input;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {

		// Register validators
		$validator = $this->app->make('validator');
		$validator->extend('unique_with', 'Muleta\Library\Laravel\Validator@uniqueWith');
		$validator->extend('file', 'Muleta\Library\Laravel\Validator@file');
		$validator->extend('video', 'Muleta\Library\Laravel\Validator@video');

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() { }

}
