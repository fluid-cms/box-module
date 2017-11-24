<?php

namespace Grapesc\GrapeFluid\AdminModule\Presenters;

use Grapesc\GrapeFluid\AdminModule\ComponentListControl\IComponentListControlFactory;
use Grapesc\GrapeFluid\BoxModule\BoxForm;
use Grapesc\GrapeFluid\BoxModule\Model\BoxesModel;
use Grapesc\GrapeFluid\FluidFormControl\FluidFormControl;
use Grapesc\GrapeFluid\BoxModule\Grid\BoxGrid;


class BoxPresenter extends BasePresenter
{

	/** @var BoxForm @inject */
	public $boxForm;

	/** @var BoxesModel @inject */
	public $boxes;

	/** @var IComponentListControlFactory @inject */
	public $componentListControlFactory;


	protected function createComponentBoxForm()
	{
		return new FluidFormControl($this->boxForm);
	}


	protected function createComponentBoxGrid()
	{
		return new BoxGrid($this->boxes, $this->translator);
	}


	/**
	 * @return \Grapesc\GrapeFluid\AdminModule\ComponentListControl\ComponentListControl
	 */
	protected function createComponentComponentList()
	{
		return $this->componentListControlFactory->create();
	}


	public function renderEdit($id = null)
	{
		/** @var FluidFormControl $component */
		$component = $this->getComponent("boxForm");
		if (is_numeric($id) && $id !== null && $box = $this->boxes->getItem($id)) {
			$component->setDefaults($box);
		} elseif (is_string($id)) {
			$component->setDefaults(["name" => $id]);
		}
		$this->template->new = $id === null;
	}

}