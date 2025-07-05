<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
// use App\Repositories\Company\CompanyInterface;
// use App\Repositories\Company\CompanyRepository;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    // $this->app->bind(CompanyInterface::class, CompanyRepository::class);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrapFive();
    require_once app_path('Helper/TextTranslate.php');
  }
}
