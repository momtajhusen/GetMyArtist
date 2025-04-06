<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IconSelect extends Component
{
    public $label;
    public $name;
    public $options;
    public $icon;

    public function __construct($label, $name, $options = [], $icon = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->options = $options;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.icon-select');
    }
}
