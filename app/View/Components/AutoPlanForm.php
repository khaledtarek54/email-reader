<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AutoPlanForm extends Component
{
    public $data;
    public $response;
    public $tasks;
    public $weekDays;
    public $weekEnd;
    public $weekDaysJson;

    public function __construct($response, $data)
    {

        $this->data = $data;
        $this->response = $response;
        $this->tasks = $response['tasks'];
        $this->weekDays = $response['weekDays'];
        $this->weekEnd = $response['weekEnd'];
        $this->weekDaysJson = json_encode($this->weekDays);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.auto-plan-form')
            ->with('response', $this->response)
            ->with('data', $this->data)
            ->with('tasks', $this->tasks)
            ->with('weekDays', $this->weekDays)
            ->with('weekEnd', $this->weekEnd)
            ->with('weekDaysJson', $this->weekDaysJson);
    }
}
