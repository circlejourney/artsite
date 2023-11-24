<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\PrivacyLevel;

class PrivacySelect extends Component
{
	public $privacylevels;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->privacylevels = PrivacyLevel::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.privacy-select');
    }
}
