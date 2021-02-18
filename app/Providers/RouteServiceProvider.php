<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
    * This namespace is applied to your controller routes.
    *
    * In addition, it is set as the URL generator's root namespace.
    *
    * @var string
    */
    protected $namespace = 'App\Http\Controllers';

    /**
    * Define your route model bindings, pattern filters, etc.
    *
    * @return void
    */
    public function boot()
    {
        //
        Route::pattern('id', '[0-9]+');
        Route::pattern('course_id', '[0-9]+');
        Route::pattern('level_id', '[0-9]+');
        parent::boot();
    }

    /**
    * Define the routes for the application.
    *
    * @return void
    */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapWebAdminRoutes();

        $this->mapWebEmployerRoutes();

        $this->mapSuperRoutes();

        $this->mapTrainerRoutes();

        $this->mapStudentRoutes();

        //
    }

    /**
    * Define the "web" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/web.php'));
    }

    /**
    * Define the "admin" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapWebAdminRoutes(){
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/admin.php'));
    }

    /**
    * Define the "admin" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapWebEmployerRoutes(){
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/employer.php'));
    }

    /**
    * Define the "super" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapSuperRoutes(){
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/super.php'));
    }

    /**
    * Define the "trainer" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapTrainerRoutes(){
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/trainer.php'));
    }

    /**
    * Define the "student" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapStudentRoutes(){
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/student.php'));
    }


    /**
    * Define the "api" routes for the application.
    *
    * These routes are typically stateless.
    *
    * @return void
    */
    protected function mapApiRoutes()
    {

        Route::prefix('api')
       ->middleware('api')
        ->namespace($this->namespace)
       ->group(base_path('routes/api.php'));
    }
}
