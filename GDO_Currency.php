<?php
namespace GDO\Currency;

use GDO\Core\GDO;
use GDO\DB\GDT_Char;
use GDO\DB\GDT_Checkbox;
use GDO\DB\GDT_Decimal;
use GDO\DB\GDT_String;
use GDO\DB\GDT_EditedAt;
use GDO\DB\GDT_UInt;

/**
 * A currency. Primary key is  the 3 letter ISO in uppercase.
 * Can convert to credits.
 * Can convert between currencies.
 * 
 * @TODO: implement currency conversion.
 * 
 * @author gizmore
 * @version 6.11.3
 * @since 6.8.0
*/
final class GDO_Currency extends GDO
{
	public function gdoCached() { return true; }
	
	###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return [
			GDT_Char::make('ccy_iso')->ascii()->caseS()->length(3)->primary(),
			GDT_String::make('ccy_symbol')->max(3)->notNull(),
			GDT_UInt::make('ccy_digits')->bytes(1)->min(1)->max(4),
			GDT_Decimal::make('ccy_ratio')->digits(6, 6),
			GDT_Checkbox::make('ccy_auto_update')->initial('1'),
			GDT_EditedAt::make('ccy_updated_at'),
		];
	}

	##############
	### Getter ###
	##############
	public function getRatio() { return $this->getVar('ccy_ratio'); }
	public function getDigits() { return $this->getVar('ccy_digits'); }
	public function getSymbol() { return $this->getVar('ccy_symbol'); }
	public function isSyncAutomated() { return $this->getVar('ccy_auto_update') === '1'; }

	################
	### Display ####
	################
	public function displayName() { return $this->displayValue($this->getRatio(), true); }
	public function displayValue($value, $with_symbol=true)
	{
		return sprintf('%s%.0'.$this->getDigits().'f',
			$with_symbol ? $this->getSymbol().' ' : '',
			$value);
	}
	
	###############
	### Factory ###
	###############
	/**
	 * @param string $iso
	 * @return self
	 */
	public static function getByISO($iso)
	{
		return self::getById($iso);
	}
	
	##################
	### Conversion ###
	##################
	public function toCredits($money)
	{
		return floor($money * 100.0);
	}
	
	public function toMoney($credits)
	{
		$money = $credits / 100.0;
		$digits = $this->getDigits();
		return sprintf("%.0{$digits}f", $money);
	}
	
	public static function convertCurrency($value, $from, $to)
	{

	}
	
}
