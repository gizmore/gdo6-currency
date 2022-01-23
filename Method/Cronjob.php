<?php
namespace GDO\Currency\Method;

use GDO\Currency\GDO_Currency;
use GDO\Currency\Module_Currency;
use GDO\Cronjob\MethodCronjob;

/**
 * Hourly sync currencies with EZB.
 * 
 * @author gizmore
 * @version 6.11.3
 * @since 6.4.0
 */
final class Cronjob extends MethodCronjob
{
	public function runAt()
	{
		return Module_Currency::instance()->cfgUpdateFrequency();
	}
	
	public function run()
	{
		$module = Module_Currency::instance();
		$this->log("Requesting ECB exchange rates");
		$xml = simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
		$this->log("Got EZB exchange rates");
		foreach($xml->Cube->Cube->Cube as $rate)
		{
			$this->syncCurrency($module, $rate['currency']->__toString(), $rate['rate']->__toString());
		}
	}
	
	private function syncCurrency(Module_Currency $module, $iso, $rate)
	{
	    if (!($currency = GDO_Currency::getByISO($iso)))
		{
		    $currency = GDO_Currency::blank([
		    	'ccy_iso' => $iso,
		    	'ccy_ratio' => $rate,
		    	'ccy_symbol' => $iso,
		    	'ccy_digits' => $this->calcDigits($rate),
		    ]);
		}
		
		if ($currency->isSyncAutomated())
		{
			$currency->setVar('ccy_ratio', $rate);
			$currency->setVar('ccy_digits', $this->calcDigits($rate));
		}

		$currency->save();
	}
	
	private function calcDigits($rate)
	{
		return strlen($rate) - (strpos($rate, '.') + 1);
	}
	
}
