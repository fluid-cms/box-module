<?php

namespace Grapesc\GrapeFluid\BoxModule\Model;

use Grapesc\GrapeFluid\Model\BaseModel;


class BoxesModel extends BaseModel
{

	/**
	 * @param string $name - Name of the box
	 * @return bool|mixed|\Nette\Database\Table\IRow
	 */
	public function getBox($name)
	{
		return $this->getItemBy($name, "name");
	}

}