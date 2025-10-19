<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Перескоков Кирилл Вадимович',
            'username' => 'k.pereskokov',
            'password' => Hash::make('AdminPassword123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
