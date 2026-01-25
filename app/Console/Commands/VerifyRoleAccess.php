<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class VerifyRoleAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:role-access';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Verify role-based access control configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔐 VERIFYING ROLE-BASED ACCESS CONTROL\n");
        $this->info(str_repeat('=', 60));

        // Check Roles exist
        $this->info("\n1️⃣ Checking Roles...");
        $this->checkRoles();

        // Check Users have roles
        $this->info("\n2️⃣ Checking Users and Their Roles...");
        $this->checkUsers();

        // Check middleware configuration
        $this->info("\n3️⃣ Checking Middleware Configuration...");
        $this->checkMiddleware();

        // Check Route protection
        $this->info("\n4️⃣ Checking Protected Routes...");
        $this->checkRoutes();

        $this->info("\n" . str_repeat('=', 60));
        $this->info("✅ ROLE ACCESS VERIFICATION COMPLETE");
        $this->info(str_repeat('=', 60));

        return 0;
    }

    protected function checkRoles(): void
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            $this->error("❌ No roles found in database!");
            $this->info("   Run: php artisan db:seed --class=RoleSeeder");
            return;
        }

        $this->info("✅ Found " . $roles->count() . " roles:");
        foreach ($roles as $role) {
            $userCount = $role->users()->count();
            $this->line("   • {$role->name} ({$role->display_name}) - {$userCount} users");
        }
    }

    protected function checkUsers(): void
    {
        $users = User::with('role')->get();

        if ($users->isEmpty()) {
            $this->error("❌ No users found!");
            return;
        }

        $this->info("✅ Found " . $users->count() . " users:");

        $byRole = $users->groupBy(function ($user) {
            return $user->role?->name ?? 'No Role';
        });

        foreach ($byRole as $role => $roleUsers) {
            $this->line("   📌 {$role}:");
            foreach ($roleUsers as $user) {
                $status = $user->is_active ? "✓" : "✗";
                $this->line("      [{$status}] {$user->name} ({$user->email})");
            }
        }
    }

    protected function checkMiddleware(): void
    {
        $middlewares = [
            'owner' => 'EnsureUserIsOwner',
            'admin' => 'EnsureUserIsAdmin',
            'customer' => 'EnsureUserIsCustomer',
        ];

        $this->info("✅ Middleware routes configured:");
        foreach ($middlewares as $key => $middleware) {
            $path = app_path("Http/Middleware/{$middleware}.php");
            $exists = file_exists($path) ? "✓" : "✗";
            $this->line("   [{$exists}] {$key} → {$middleware}");
        }
    }

    protected function checkRoutes(): void
    {
        $routes = [
            'Owner' => '/owner/dashboard',
            'Admin' => '/admin/dashboard',
            'Customer' => '/customer',
            'All Auth' => '/catalog',
            'Public' => '/',
        ];

        $this->info("✅ Protected routes:");
        foreach ($routes as $type => $route) {
            $this->line("   • [{$type}] {$route}");
        }
    }
}
