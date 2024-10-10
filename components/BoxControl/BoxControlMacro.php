<?php

namespace Grapesc\GrapeFluid\BoxModule\Control;

use Latte\Extension;


class BoxControlMacro extends Extension
{

	public function getTags(): array
	{
		return [
			'box' => [BoxControlNode::class, 'create'],
		];
	}

}