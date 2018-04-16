<?php

namespace Grapesc\GrapeFluid\BoxModule;

use Grapesc\GrapeFluid\BoxModule\Model\BoxesModel;
use Grapesc\GrapeFluid\FluidFormControl\FluidForm;
use Grapesc\GrapeFluid\MagicControl\Helper;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


class BoxForm extends FluidForm
{

	/**  @var BoxesModel @inject */
	public $boxes;


	protected function build(Form $form)
	{
		$form->addHidden("id");

		$form->addText("title", "Titulek")
			->setRequired(false)
			->setAttribute("cols", 6)
			->setAttribute("help", "Slouží jako vaše označení")
			->addRule(Form::MAX_LENGTH, "Délka titulku může být max. %s znaků", 250);

		$form->addText("name", "Jméno / Identifikátor")
			->setAttribute("cols", 6)
			->setAttribute("help", "Slouží pro volání a identifikaci v kódu (může obsahovat pouze písmena [a-z] a být dlouhý 3 až 20 znaků).")
			->setRequired("Jméno / Identifikátor je povinné pole")
			->addRule(Form::PATTERN, "Zadaný identifikátor je neplatný", "[a-z]{3,20}");

		$form->addTextArea("content", "Obsah")
			->setAttribute("class", "form-summernote");

		$form->addTextArea("note", "Vaše poznámka");

		$form->addCheckbox("disabled", "Vypnout box")
			->setOption('cols', 3);

		$form->addCheckbox("editable", "Upravitelný z webu")
			->setOption('cols', 3);
	}


	protected function addButtons(Form $form)
	{
		parent::addButtons($form);
		$form->addSubmit("stay", "Uložit a zůstat")
			->setAttribute('class', 'btn btn-info');
	}


	public function onSubmitEvent(Control $control, Form $form)
	{
		parent::onSubmitEvent($control, $form);

		$presenter = $control->getPresenter();
		$values = $form->getValues(true);

		$box = $this->boxes->getBox($values['name']);

		if (($values['id'] == "" && $box) || ($values['id'] != "" && $box && $box->id != $values['id'])) {
			$form->addError("Jméno / Identifikátor je již použit u jiného boxu!");
			$presenter->redrawControl("boxControl");
			return;
		}

		$id = null;

		if ($values['id'] == "") {
			unset($values['id']);
			$values['content'] = Helper::createSafeEscapeString($values['content']);
			$id = $this->boxes->insert($values)->id;
			$presenter->flashMessage("Box byl úspěšně vytvořen", "success");
		} else {
			$values['content'] = Helper::createSafeEscapeString($values['content']);
			$this->boxes->update($values, $values['id']);
			$presenter->flashMessage("Box byl úspěšně upraven", "success");
		}


		if ($form->isSubmitted()->control->name == "stay") {
			$presenter->redirect(":Admin:Box:edit", ["id" => ($id == null ? $values['id'] : $id)]);
		} else {
			$presenter->redirect(":Admin:Box:default");
		}
	}

}