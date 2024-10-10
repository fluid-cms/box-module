<?php

namespace Grapesc\GrapeFluid\BoxModule\Grid;

use Grapesc\GrapeFluid\FluidGrid;
use Grapesc\GrapeFluid\MagicControl\Helper;
use Nette\Database\Table\ActiveRow;


class BoxGrid extends FluidGrid
{

	protected function build(): void
	{
		$this->skipColumns(["content", "name", "disabled", "editable", "base"]);
		$this->setSortableColumns(["title"]);
		$this->setItemsPerPage(50);

		parent::build();

		$this->addColumn("usage");
		$this->addColumn("name");
		$this->addRowAction("edit", "Upravit", function(ActiveRow $record) {
			$this->getPresenter()->redirect(":Admin:Box:edit", ["id" => $record->id]);
		});
		$this->addRowAction("disable", "Vypnout", function(ActiveRow $record) {
			$record->update(["disabled" => !$record->disabled]);
		});
		$this->addRowAction("editable", "Upravitelný", function(ActiveRow $record) {
			$record->update(["editable" => !$record->editable]);
		});
		$this->addRowAction("delete", "Smazat", function (ActiveRow $record) {
			if (!$record->base) {
				$this->model->delete($record->id);
				$this->flashMessage("Box smazán", "success");
			}
		});
	}

}