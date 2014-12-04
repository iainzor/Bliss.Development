<?php
namespace Tests;

use Router\ProviderInterface as RouteProvider;

class Module extends \Bliss\Module\AbstractModule implements RouteProvider
{
	public function init()
	{}
	
	public function initRouter(\Router\Module $router) 
	{
		$router->when("/^tests/i", [], [
			"module" => "tests",
			"controller" => "runner",
			"action" => "run"
		]);
	}
}