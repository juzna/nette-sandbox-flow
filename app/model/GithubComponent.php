<?php


/**
 * Show last updated project
 */
class GithubComponent extends BaseControl
{
	/** @var React\HttpClient\Client */
	public $httpClient;

	/** @var string */
	private $name;


	public function __construct(\React\HttpClient\Client $client, $name)
	{
		parent::__construct();
		$this->httpClient = $client;
		$this->name = $name;
	}


	public function renderFlow()
	{
		$data = (yield $this->httpClient->request('GET', "https://github.com/$this->name.json")->getResponseBody());
		$events = Nette\Utils\Json::decode($data, Nette\Utils\Json::FORCE_ARRAY);
		if (!isset($events[0])) {
			yield result("No info");
		}
		$event = $events[0];

		$url = str_replace('https://', 'https://raw.', $event['repository']['url']) . '/master/composer.json';
		$composerData = (yield $this->httpClient->request('GET', $url)->getResponseBody());

		if ($composer = json_decode($composerData, JSON_OBJECT_AS_ARRAY)) {
			yield result("Last change to composer project $composer[name]");

		} else {
			yield result("Last change to github repo {$event['repository']['url']}");
		}
	}

}
