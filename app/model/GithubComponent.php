<?php

use Nette\Utils\Json;


/**
 * Show last updated project
 */
class GithubComponent extends Flow\BaseControl
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
		list($data) = (yield $this->httpClient->get("https://github.com/$this->name.json"));
		$events = Json::decode($data, Json::FORCE_ARRAY);
		if (!isset($events[0])) {
			yield result("No info");
		}
		$event = $events[0];

		$composerUrl = str_replace('https://', 'https://raw.', $event['repository']['url']) . '/master/composer.json';
		list($composerData) = (yield $this->httpClient->get($composerUrl));

		if ($composer = json_decode($composerData, JSON_OBJECT_AS_ARRAY)) {
			yield result("Last change to composer project $composer[name]");

		} else {
			yield result("Last change to github repo {$event['repository']['url']}");

		}
	}

}
