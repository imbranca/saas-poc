<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    User::insert([
      [
        'id' => '1',
        'name' => 'Manager',
        'email' => 'manager@example.com',
        'password' => Hash::make('Password#123'),
        'role' => Roles::MANAGER,
        'created_at' => Carbon::now()
      ],
      [
        'id' => '2',
        'name' => 'Viewer',
        'email' => 'viewer@example.com',
        'password' => Hash::make('Password#123'),
        'role' => Roles::VIEWER,
        'created_at' => Carbon::now()
      ],
    ]);
  }
}
