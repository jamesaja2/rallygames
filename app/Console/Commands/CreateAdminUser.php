<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--email= : Email address for the admin user}
                            {--name= : Name for the admin user}
                            {--password= : Password for the admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Super Admin user for Rally Games';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Rally Games - Create Super Admin User');
        $this->line('');

        // Get user input
        $email = $this->option('email') ?: $this->ask('Email address');
        $name = $this->option('name') ?: $this->ask('Full name');
        $password = $this->option('password') ?: $this->secret('Password (min 8 characters)');

        // Validate input
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|min:2',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line("  ❌ {$error}");
            }
            return Command::FAILURE;
        }

        try {
            // Ensure Super Admin role exists
            $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Assign Super Admin role
            $user->assignRole('Super Admin');

            $this->line('');
            $this->info('✅ Super Admin user created successfully!');
            $this->line('');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Name', $name],
                    ['Email', $email],
                    ['Role', 'Super Admin'],
                    ['Created', $user->created_at->format('Y-m-d H:i:s')],
                ]
            );
            $this->line('');
            $this->info('🔗 You can now login at: ' . config('app.url') . '/admin');
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Failed to create user: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
