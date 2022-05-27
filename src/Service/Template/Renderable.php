<?php
declare(strict_types=1);


namespace App\Service\Template;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class Renderable
{

	protected Environment $twig;

	/**
	 * @required
	 * @return static
	 */
	public function getTwig(Environment $twig): static{
		$new = clone $this;
		$new->twig = $twig;

		return $new;
	}

	/**
	 * Returns rendered template as string
	 *
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function render(): string{
		$variables = $this->getTemplateVariables();
		return $this->twig->render($this->getTemplatePath(),$this->getTemplateVariables());
	}

	public abstract function getTemplatePath(): string;

	/**
	 * @return array<string,mixed>
	 */
	public abstract function getTemplateVariables(): array;

}