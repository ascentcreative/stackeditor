<?php

namespace AscentCreative\StackEditor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\Router;

class StackEditorServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
   
    $this->mergeConfigFrom(
        __DIR__.'/../config/stackeditor.php', 'stackeditor'
    );


    // Register the helpers php file which includes convenience functions:
    require_once (__DIR__.'/../helpers/stackeditor-helpers.php');

  }

  public function boot()
  {

    $this->loadViewsFrom(__DIR__.'/../resources/views', 'stackeditor');

    $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    $this->bootComponents();

    $this->bootPublishes();

  }

  

  // register the components
  public function bootComponents() {

    Blade::component('stackeditor', 'AscentCreative\StackEditor\View\Components\Stack');
    Blade::component('stackeditor-row', 'AscentCreative\StackEditor\View\Components\Row');
    Blade::component('stackeditor-block', 'AscentCreative\StackEditor\View\Components\Block');

  }




  

    public function bootPublishes() {

      $this->publishes([
        __DIR__.'/Assets' => public_path('vendor/ascent/stackeditor'),
    
      ], 'public');

      $this->publishes([
        __DIR__.'/../config/stackeditor.php' => config_path('stackeditor.php'),
      ]);

    }



}