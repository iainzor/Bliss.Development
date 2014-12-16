<?php
namespace Tests;

use Bliss\Module\AbstractModule,
	Router\ProviderInterface as RouteProvider,
	View\Partial\InjectorInterface,
	View\Partial\InjectableInterface,
	View\Partial\Partial,
	UnifiedUI\Module as UI;

class Module extends AbstractModule implements RouteProvider, InjectorInterface
{
	public function init()
	{}
	
	public function initRouter(\Router\Module $router) 
	{
		$router->when("/^tests\.([a-z0-9]+)?$/i", [
			1 => "format"
		], [
			"module" => "tests",
			"controller" => "runner",
			"action" => "run"
		]);
	}
	
	public function initPartialInjector(InjectableInterface $injectable) 
	{
		$injectable->inject(UI::AREA_HEADER_WIDGETS, new Partial($this->resolvePath("layouts/partials/status.phtml")));
	}
}