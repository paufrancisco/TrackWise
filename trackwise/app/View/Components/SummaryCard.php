<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SummaryCard extends Component
{
    /** @var string */
    public string $label;

    /** @var float */
    public float $value;

    /** @var string */
    public string $subtitle;

    /** @var string */
    public string $bgColor;

    /** @var string */
    public string $valueColor;

    /** @var string */
    public string $labelColor;

    /**
     * Create a new component instance.
     *
     * @param string $label The card's heading label
     * @param float $value The monetary value to display
     * @param string $subtitle Secondary label below the value
     * @param string $bgColor Tailwind background color class
     * @param string $valueColor Tailwind text color for the value
     * @param string $labelColor Tailwind text color for labels
     */
    public function __construct(
        string $label,
        float $value,
        string $subtitle = '',
        string $bgColor = 'bg-white',
        string $valueColor = 'text-gray-900',
        string $labelColor = 'text-gray-500'
    ) {
        $this->label = $label;
        $this->value = $value;
        $this->subtitle = $subtitle;
        $this->bgColor = $bgColor;
        $this->valueColor = $valueColor;
        $this->labelColor = $labelColor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.summary-card');
    }
}