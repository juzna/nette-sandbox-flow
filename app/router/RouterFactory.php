<?php

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		if (PHP_SAPI === 'cli') $router[] = new \Nette\Application\Routers\CliRouter(['action' => 'Homepage:default']);
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}

}
