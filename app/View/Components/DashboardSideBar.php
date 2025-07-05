<?php

   namespace App\View\Components;

   use Closure;
   use Illuminate\Contracts\View\View;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\View\Component;

   class DashboardSideBar extends Component
   {
      public $messageCount;

      /**
       * Create a new component instance.
       */
      public function __construct($messageCount)
      {
         $user = Auth::guard('admin')->user();
         $messageCount = $user->unReadNotifications()->count();
      }

      /**
       * Get the view / contents that represent the component.
       */
      public function render(): View|Closure|string
      {
         return view('components.dashboard.dashboard-side-bar');
      }
   }