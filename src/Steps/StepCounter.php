<?php declare(strict_types = 1);

namespace Contributte\FormWizard\Steps;

use Contributte\FormWizard\Session\WizardSessionSection;
use Nette\SmartObject;

final class StepCounter
{

	use SmartObject;

	/** @var WizardSessionSection */
	private $section;

	/** @var int */
	private $maxSteps;

	public function __construct(WizardSessionSection $section, int $maxSteps)
	{
		$this->section = $section;
		$this->maxSteps = $maxSteps;
	}

	public function getCurrentStep(): int
	{
		return $this->minmax($this->section->getCurrentStep() ?: 1);
	}

	public function getLastStep(): int
	{
		return $this->minmax($this->section->getLastStep() ?: 1);
	}

	public function setLastStep(int $step): void
	{
		$last = $this->getLastStep();
		if ($last > $step) {
			return;
		}

		$this->section->setLastStep($this->minmax($step));
	}

	public function setCurrentStep(int $step, bool $checkLastStep = true): void
	{
		if ($checkLastStep && $step > $this->getLastStep()) {
			$step = $this->getLastStep();
		}

		$this->section->setCurrentStep($this->minmax($step));
	}

	public function nextStep(): void
	{
		$step = $this->getCurrentStep() + 1;
		$this->setLastStep($step);
		$this->setCurrentStep($step);
	}

	public function previousStep(): void
	{
		$this->setCurrentStep($this->getCurrentStep() - 1);
	}

	protected function minmax(int $value, int $min = 1, ?int $max = null): int
	{
		return min(max($value, $min), $max ?? $this->maxSteps);
	}
	
}
