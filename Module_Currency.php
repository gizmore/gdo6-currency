<?php
namespace GDO\Currency;

use GDO\Core\GDO_Module;
use GDO\Date\GDT_Duration;
use GDO\Date\GDT_Timestamp;
use GDO\Date\Time;
use GDO\DB\GDT_String;
/**
 * Builds a list of currency and conversion rates.
 * Updates them via cronnjob.
 * 
 * @author gizmore
 * @version 5.00
 */
final class Module_Currency extends GDO_Module
{
	##############
	### Module ###
	##############
	public $module_priority = 10;
	public function onLoadLanguage() { return $this->loadLanguage('lang/currency'); }
	public function getClasses() { return array('GDO\Currency\GDO_Currency'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDT_Timestamp::make('ccy_last_try')->initial(0),
			GDT_String::make('ccy_last_sync'),
			GDT_Duration::make('ccy_update_fqcy')->initial("1h"),
		);
	}
	public function cfgUpdateEnabled() { return $this->cfgUpdateFrequency() > 0; }
	public function cfgLastTry() { return $this->getConfigValue('ccy_last_try'); }
	public function cfgLastSync() { return $this->getConfigValue('ccy_last_sync'); }
	public function cfgUpdateFrequency() { return $this->getConfigValue('ccy_update_fqcy'); }
	
}
