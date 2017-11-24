<?php

namespace Grapesc\GrapeFluid\BoxModule\Control;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;


class BoxControlMacro extends MacroSet
{

	public static function install(Compiler $compiler)
	{
		$m = new static($compiler);
		$m->addMacro('box', array($m, 'boxWriter'));
		return $m;
	}


	public function boxWriter(MacroNode $node, PhpWriter $writer)
	{
		$w = $writer->write('echo $this->global->uiControl->getComponent("mc_box_" . %node.word)->render([%node.word])');
		return $w;
	}

}