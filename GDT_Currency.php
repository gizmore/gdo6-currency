<?php
namespace GDO\Currency;

use GDO\DB\GDT_ObjectSelect;

final class GDT_Currency extends GDT_ObjectSelect
{
	public function __construct()
	{
		parent::__construct();
		$this->table(GDO_Currency::table());
	}
	
	public $supported = false;
	public function supported($supported=true)
	{
		$this->supported = $supported;
		return $this;
	}
	
	public function getChoices()
	{
		if ($this->supported)
		{
			return Module_Currency::instance()->cfgSupported();
		}
		return $this->table ? $this->table->allCached() : [];
	}
	
	
	
}
