<?php
namespace GDO\Currency;
use GDO\Core\GDO;
use GDO\DB\GDT_Char;
use GDO\DB\GDT_Checkbox;
use GDO\DB\GDT_Decimal;
use GDO\DB\GDT_Int;
use GDO\DB\GDT_String;
use GDO\DB\GDT_EditedAt;
/**
 * @author gizmore
*/
final class GDO_Currency extends GDO
{
// 	public function gdoCached() { return false; }
	
	###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
			GDT_Char::make('ccy_iso')->ascii()->caseS()->length(3)->primary(),
			GDT_String::make('ccy_symbol')->max(3)->notNull(),
			GDT_Int::make('ccy_digits')->bytes(1)->unsigned()->min(1)->max(4),
			GDT_Decimal::make('ccy_ratio')->digits(6, 6),
			GDT_Checkbox::make('ccy_auto_update')->initial('1'),
			GDT_EditedAt::make('ccy_updated_at'),
		);
	}

	##############
	### Getter ###
	##############
	public function getSymbol() { return $this->getVar('ccy_symbol'); }
	public function isSyncAutomated() { return $this->getVar('ccy_auto_update') === '1'; }

	################
	### Display ####
	################
	public function displayValue($value, $with_symbol=true) { return sprintf('%s%.02f'.$this->getVar('curr_digits').'f', $with_symbol ? $this->getSymbol().'' : '', $value); }

	###############
	### Factory ###
	###############
// 	/**
// 	* Return all available ISOs.
// 	*/
// 	public static function getISOs()
// 	{
// 		return self::table()->selectColumn('curr_iso');
// 	}

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
	public static function convert($value, $from, $to)
	{

	}
}
