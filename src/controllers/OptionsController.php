<?php
namespace Tecnolaw\Authorization\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Tecnolaw\Authorization\Models\Region;
use Tecnolaw\Authorization\Models\Commune;

class OptionsController extends BaseController
{
	protected $region=null;
	protected $commune=null;

	function __construct()
	{
		$this->region = new Region;
		$this->commune = new Commune;
	}
	public function listRegion()
	{
		$regions=$this->region->select('id as value','name as label');

		return $regions->get();
	}
	public function listCommune()
	{
		$communes=$this->commune->select('id as value','name as label','region_id');
		return $communes->get();
		
	}
}