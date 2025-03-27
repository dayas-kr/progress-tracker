<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class BaseLayout extends Component
{
    public $title;

    /**
     * Create a new component instance.
     *
     * @param string|null $title
     */
    public function __construct($title = null)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.base');
    }
}
