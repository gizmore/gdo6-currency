<?php
namespace GDO\Currency;

use GDO\Core\GDO_Module;
use GDO\Cronjob\GDT_RunAt;
use GDO\Date\Time;
use GDO\User\GDO_User;

/**
 * Builds a list of currency and conversion rates.
 * Configure currency for site default, supported currencies and user currency.
 * Updates them daily via a cronnjob.
 * 
 * @author gizmore
 * @version 6.11.3
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

	/**
	 * On install creates the EUR currency.
	 */
	public function onInstall()
	{
		if (!GDO_Currency::getByISO('EUR'))
		{
			GDO_Currency::blank([
				'ccy_iso' => 'EUR',
				'ccy_symbol' => '€',
				'ccy_digits' => '2',
				'ccy_ratio' => '1.00',
				'ccy_auto_update' => '0',
				'ccy_updated_at' => Time::getDate(),
			])->insert();
		}
	}
	
	##############
	### Config ###
	##############
	public function getConfig()
	{
		return [
			GDT_Currency::make('ccy_site')->initial("EUR"),
			GDT_Currency::make('ccy_supported')->multiple()->initial("[\"EUR\", \"USD\"]"),
			GDT_RunAt::make('ccy_update_fqcy')->initial("5 /2 * * *"),
		];
	}
	
	/**
	 * @return GDO_Currency
	 */
	public function cfgCurrency() { return $this->getConfigValue('ccy_site'); }
	
	/**
	 * @return GDO_Currency[]
	 */
	public function cfgSupported() { return $this->getConfigValue('ccy_supported'); }
	
	public function cfgUpdateFrequency() { return $this->getConfigVar('ccy_update_fqcy'); }
	
	################
	### Settings ###
	################
	public function getUserSettings()
	{
		return [
			GDT_Currency::make('currency')->notNull()->initial('EUR'),
		];
	}
	
	/**
	 * @param GDO_User $user
	 * @return GDO_Currency
	 */
	public function cfgUserCurrency(GDO_User $user=null)
	{
		$user = $user ? $user : GDO_User::current();
		return $this->userSettingValue($user, 'currency');
	}
	
	public function cfgUserCurrencyId(GDO_User $user=null)
	{
		return $this->cfgUserCurrency($user)->getID();
	}

}
