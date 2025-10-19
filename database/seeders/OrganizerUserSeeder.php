<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OrganizerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Преподаватель 1
        User::create([
            'name' => 'Иванов Иван Иванович',
            'username' => 'i.ivanov',
            'password' => Hash::make('Password123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_ORGANIZER,
        ]);

        // Преподаватель 2
        User::create([
            'name' => 'Петрова Мария Сергеевна',
            'username' => 'm.petrova',
            'password' => Hash::make('Password123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_ORGANIZER,
        ]);

        // Преподаватель 3
        User::create([
            'name' => 'Сидоров Алексей Владимирович',
            'username' => 'a.sidorov',
            'password' => Hash::make('Password123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_ORGANIZER,
        ]);
    }
}
