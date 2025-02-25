<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            $this->mapWebRoutes();
            $this->mapAuthRoutes();
            $this->mapAdminRoutes();
            $this->mapTeacherRoutes();
            $this->mapStudentRoutes();
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "auth" routes for the application.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::middleware(['web'])
             ->prefix('Authenticate')
             ->group(base_path('routes/auth.php'));
    }
    /**
     * Define the "admin" routes for the application.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'auth', 'admin'])
             ->prefix('Admin')
             ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "teacher" routes for the application.
     *
     * @return void
     */
    protected function mapTeacherRoutes()
    {
        Route::middleware(['web', 'auth', 'teacher'])
             ->prefix('Teacher')
             ->group(base_path('routes/teacher.php'));
    }

      /**
     * Define the "student" routes for the application.
     *
     * @return void
     */
    protected function mapStudentRoutes()
    {
        Route::middleware(['web', 'auth', 'student'])
             ->prefix('Student')
             ->group(base_path('routes/student.php'));
    }

}