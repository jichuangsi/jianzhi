<?php
namespace App\Modules\Jianzhi\Providers;

use App;
use Config;
use Lang;
use View;
use Illuminate\Support\ServiceProvider;

class JianzhiServiceProvider extends ServiceProvider
{
	
	public function register()
	{
		
		
		
		App::register('App\Modules\Jianzhi\Providers\RouteServiceProvider');

		$this->registerNamespaces();
	}

	
	protected function registerNamespaces()
	{
		Lang::addNamespace('jianzhi', realpath(__DIR__.'/../Resources/Lang'));

		View::addNamespace('jianzhi', base_path('resources/views/vendor/jianzhi'));
		View::addNamespace('jianzhi', realpath(__DIR__.'/../Resources/Views'));
	}
}
