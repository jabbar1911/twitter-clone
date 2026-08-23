<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public mixed $whoToFollow = null,
        public ?string $search = null,
        public ?string $title = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
