<?php
namespace GDO\Currency;

use GDO\DB\GDO;
use GDO\DB\GDO_UpdatedAt;
use GDO\Type\GDO_Char;
use GDO\Type\GDO_Checkbox;
use GDO\Type\GDO_Decimal;
use GDO\Type\GDO_Int;
use GDO\Type\GDO_String;
/**
 * @author gizmore
*/
final class Currency extends GDO
{
// 	public function gdoCached() { return false; }
	
	###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
			GDO_Char::make('ccy_iso')->ascii()->caseS()->size(3)->primary(),
			GDO_String::make('ccy_symbol')->max(3)->notNull(),
			GDO_Int::make('ccy_digits')->bytes(1)->unsigned()->min(1)->max(4),
			GDO_Decimal::make('ccy_ratio')->digits(6, 6),
			GDO_Checkbox::make('ccy_auto_update')->initial('1'),
			GDO_UpdatedAt::make('ccy_updated_at'),
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
