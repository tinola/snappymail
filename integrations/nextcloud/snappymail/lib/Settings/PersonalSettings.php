<?php
namespace OCA\SnappyMail\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\Settings\ISettings;

class PersonalSettings implements ISettings
{
	private $config;

	public function __construct(IConfig $config)
	{
		$this->config = $config;
	}

	public function getForm()
	{
		$uid = \OC::$server->getUserSession()->getUser()->getUID();
		$sEmail = $this->config->getUserValue($uid, 'snappymail', 'snappymail-email');
		if ($sPass = $this->config->getUserValue($uid, 'snappymail', 'snappymail-password')) {
			$this->config->deleteUserValue($uid, 'snappymail', 'snappymail-password');
			$this->config->setUserValue($uid, 'snappymail', 'passphrase', $sPass);
		}
		$parameters = [
			'snappymail-email' => $sEmail,
			'snappymail-password' => $this->config->getUserValue($uid, 'snappymail', 'passphrase') ? '******' : ''
		];
		\OCP\Util::addScript('snappymail', 'snappymail');
		return new TemplateResponse('snappymail', 'personal_settings', $parameters, '');
	}

	public function getSection()
	{
		return 'additional';
	}

	public function getPriority()
	{
		return 50;
	}
}
