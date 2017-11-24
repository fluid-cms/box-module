<?php

namespace Grapesc\GrapeFluid\BoxModule\Control;

use Grapesc\GrapeFluid\BoxModule\Model\BoxesModel;
use Grapesc\GrapeFluid\CoreModule\Model\SettingModel;
use Grapesc\GrapeFluid\ImageStorage;
use Grapesc\GrapeFluid\MagicControl\BaseMagicControl;
use Grapesc\GrapeFluid\MagicControl\Helper;
use Nette\Http\Request;


class BoxControl extends BaseMagicControl
{

	/** @var BoxesModel @inject */
	public $boxes;

	/** @var SettingModel @inject */
	public $setting;

	/** @var Request @inject */
	public $httpRequest;

	/** @var ImageStorage @inject */
	public $imageStorage;

	/** @var string $name - Name of box */
	private $name;


	/**
	 * @param array $params
	 */
	public function setParams(array $params = [])
	{
		$params = array_values($params);
		if (isset($params[0]) && $params[0] != "") {
			$this->name = $params[0];
		} else {
			 throw new \InvalidArgumentException("Box name not set - You must use {box 'name'}");
		}
	}


	/**
	 * @return void
	 */
	public function prepare()
	{
		if ($box = $this->boxes->getBox($this->name)) {
			$template = $this->createTemplate();
			$template->setFile(__DIR__ . "/script.latte");
			$template->inlineEnabled = $this->setting->getVal("box.edit.inline");
			$template->box = $box;
			$this->scriptCollector->push($template);
		}
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/template.latte');

		# TODO: Cache?
		if ($box = $this->boxes->getBox($this->name)) {
			$this->template->inlineEnabled = $this->setting->getVal("box.edit.inline");
			$this->template->box = $box;
			$this->template->content = $box->disabled ? "" : Helper::magicMacroCreator($box->content, $this);
		} else {
			$this->template->name = $this->name;
			$this->template->content = null;
		}

		$this->template->render();
	}


	public function handleInlineEditBox()
	{
		$presenter = $this->getPresenter();

		if ($presenter->isAjax() && $presenter->getUser()->isAllowed('box')) {
			$box = $this->httpRequest->getPost("unique");
			$content = Helper::createSafeEscapeString(Helper::magicMacroRecreator($this->httpRequest->getPost("content")));
			$this->boxes->update(["content" => $content], $box, "name");
			$presenter->flashMessage("Změny v boxu '$box' uloženy", "success");
			$presenter->redrawControl("flashMessages");
		}
	}


	public function handleUploadImage()
	{
		$presenter = $this->getPresenter();

		if ($presenter->getUser()->isAllowed('backend:box')) {
			if ($process = $presenter->imageStorage->processImageFromRequest()) {
				$presenter->payload->path = $process;
			}
			$presenter->flashMessage($this->imageStorage->getLastState());
			$presenter->redrawControl("flashMessages");
		}
	}

}
