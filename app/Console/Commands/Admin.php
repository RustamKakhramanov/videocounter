<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class Admin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create  {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        //
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('What is admin name?');
        $email = $this->option('email') ?? $this->ask('What is admin email?');
        $password = $this->option('password') ?? $this->secret('What is admin password?');

        if ($name && $email && $password) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'role' => \App\Enums\UserRole::ADMIN,
            ]);
            $this->info('Administrator account created');
        }
    }
}
