<?php

namespace App\Providers;

use App\Policies\BookPolicy;
use App\Policies\UserPolicy;
use App\Policies\FacultyPolicy;
use App\Policies\PermissionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // Model::class => ModelPolicy::class,
        'App\Models\Book' => BookPolicy::class,
        'App\Models\Borrow' => BorrowPolicy::class,
        'App\Models\Faculty' => FacultyPolicy::class,
        'App\Models\Index' => IndexPolicy::class,
        'App\Models\Permission' => PermissionPolicy::class,
        'App\Models\Role' => RolePolicy::class,
        'App\Models\Student' => StudentPolicy::class,
        'App\Models\Teacher' => TeacherPolicy::class,
        'App\Models\User' => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Gate::resource('users', UserPolicy::class);
        // Gate::define('delete-user', [UserPolicy::class, 'destroy']);
    }
}
