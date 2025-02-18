<?php

namespace Grapesc\GrapeFluid\BoxModule\Control;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Tag;
use Latte\Compiler\PrintContext;


class BoxControlNode extends AreaNode
{

	public ExpressionNode $subject;


	public static function create(Tag $tag): self
	{
		$node          = new self;
		$node->subject = $tag->parser->parseUnquotedStringOrExpression();

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo $this->global->uiControl->getComponent("mc_box_" . %node)->render([%node]);',
			$this->subject,
			$this->subject,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->subject;
	}

}