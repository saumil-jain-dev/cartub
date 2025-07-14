<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;


class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions from config/modules.php into database without duplicates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $modules = config('modules');
        $this->info('🔄 Syncing permissions…');
        foreach ($modules as $moduleKey => $module) {
            $moduleName = $module['name'] ?? ucfirst($moduleKey);
            foreach ($module['actions'] as $action) {
                $permissionName = "{$moduleKey}.{$action}";

                $exists = Permission::where('name', $permissionName)->exists();
                if (! $exists) {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ]);
                    $this->info("✅ Added permission: {$permissionName}");
                } else {
                    $this->line("⚠️ Already exists: {$permissionName}");
                }
            }
        }

        $this->info('🎉 Permissions sync complete.');
        return 0;
    }
}
