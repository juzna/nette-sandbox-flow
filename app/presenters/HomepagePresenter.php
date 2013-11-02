<?php


class HomepagePresenter extends \Flow\BasePresenter
{

	protected function startup()
	{
		parent::startup();
		Flow\Flow::register(new Flow\Schedulers\HorizontalScheduler($this->context->eventLoop));
	}


	/**
	 * Render components asynchronously
	 */
	public function actionDefault()
	{
		if (($coop = $this->getParameter('coop')) !== NULL) {
			Flow\Nette\Helpers::$async = (bool) $coop;
		}

		$this->addComponent(new GithubComponent($this->context->httpClient, 'juzna'), 'ghJuzna');
		$this->addComponent(new GithubComponent($this->context->httpClient, 'hosiplan'), 'ghHosiplan');
		$this->addComponent(new GithubComponent($this->context->httpClient, 'kaja47'), 'ghKaja');
		$this->addComponent(new GithubComponent($this->context->httpClient, 'dg'), 'ghDg');
	}


	/**
	 * Simulates computing in model (which shall not be in presenter, but it doesn't really matter)
	 * Also compares sync vs async version.
	 */
	public function actionComputeInModel()
	{
		echo '<pre>';

		// generator
		$loader = function($name) {
			list($data) = (yield $this->context->httpClient->get("https://github.com/$name.json"));
			$events = Nette\Utils\Json::decode($data, Nette\Utils\Json::FORCE_ARRAY);
			if (!isset($events[0])) {
				yield result("No info");
			} else {
				yield result($events[0]['repository']['name']);
			}
		};

		$names = [
			'juzna',
			'hosiplan',
			'dg',
			'kaja47',
			'lopo',
			'janmarek',
			'jantvrdik',
			'hrach',
		];


		// try sync version
		$t = microtime(TRUE);
		$scheduler = new Flow\Schedulers\NaiveScheduler($this->context->eventLoop);
		dump($scheduler->flow(array_map(function($name) use ($loader) { return $loader($name); }, array_combine($names, $names))));
		var_dump(microtime(TRUE) - $t);

		// try async version
		$t = microtime(TRUE);
		$scheduler = new Flow\Schedulers\HorizontalScheduler($this->context->eventLoop);
		dump($scheduler->flow(array_map(function($name) use ($loader) { return $loader($name); }, array_combine($names, $names))));
		var_dump(microtime(TRUE) - $t);

		$this->terminate();
	}

}
