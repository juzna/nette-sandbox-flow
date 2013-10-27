<?php


class HomepagePresenter extends BasePresenter
{

	public function actionDefault()
	{
		$this->addComponent(new GithubComponent($this->context->httpClient, 'juzna'), 'ghJuzna');
		$this->addComponent(new GithubComponent($this->context->httpClient, 'hosiplan'), 'ghHosiplan');
		$this->addComponent(new GithubComponent($this->context->httpClient, 'kaja47'), 'ghKaja');
		$this->addComponent(new GithubComponent($this->context->httpClient, 'dg'), 'ghDg');
	}

}
