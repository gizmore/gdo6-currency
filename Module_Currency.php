<?php
namespace GDO\Currency;

use GDO\Core\GDO_Module;
use GDO\Cronjob\GDT_RunAt;
use GDO\Date\Time;

/**
 * Builds a list of currency and conversion rates.
 * Updates them via cronnjob.
 * 
 * @author gizmore
 * @version 6.11.1
 * @since 5.0.0
 */
final class Module_Currency extends GDO_Module
{
	##############
	### Module ###
	##############
	public $module_priority = 10;
	public function onLoadLanguage() { return $this->loadLanguage('lang/currency'); }
	public function getClasses() { return [GDO_Currency::class]; }
	public function getDependencies() { return ['Cronjob']; }

	public function onInstall()
	{
		if (!($eur = GDO_Currency::getByISO('EUR')))
		{
			$eur = GDO_Currency::blank([
				'ccy_iso' => 'EUR',
				'ccy_symbol' => '€',
				'ccy_digits' => '2',
				'ccy_ratio' => '1.00',
				'ccy_auto_update' => '0',
				'ccy_updated_at' => Time::getDate(),
			]);
		}
		$eur->save();
	}
	
	##############
	### Config ###
	##############
	public function getConfig()
	{
		return [
			GDT_Currency::make('ccy_supported')->multiple()->initial("[\"EUR\", \"USD\"]"),
			GDT_RunAt::make('ccy_update_fqcy')->initial("5 /2 * * *"),
		];
	}
	/**
	 * @return GDO_Currency[]
	 */
	public function cfgSupported() { return $this->getConfigValue('ccy_supported'); }
	public function cfgUpdateFrequency() { return $this->getConfigVar('ccy_update_fqcy'); }
	
}
