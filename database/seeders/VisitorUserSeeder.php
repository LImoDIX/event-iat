<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class VisitorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Студент 1
        User::create([
            'name' => 'Смирнова Анастасия Владимировна',
            'username' => 'a.smirnova',
            'password' => Hash::make('StudentPassword123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_VISITOR,
        ]);

        // Студент 2
        User::create([
            'name' => 'Кузнецов Дмитрий Алексеевич',
            'username' => 'd.kuznetsov',
            'password' => Hash::make('StudentPassword123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_VISITOR,
        ]);

        // Студент 3
        User::create([
            'name' => 'Попова Екатерина Игоревна',
            'username' => 'e.popova',
            'password' => Hash::make('StudentPassword123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_VISITOR,
        ]);

        // Студент 4
        User::create([
            'name' => 'Лебедев Михаил Сергеевич',
            'username' => 'm.lebedev',
            'password' => Hash::make('StudentPassword123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_VISITOR,
        ]);

        // Студент 5
        User::create([
            'name' => 'Новикова Ольга Дмитриевна',
            'username' => 'o.novikova',
            'password' => Hash::make('StudentPassword123!'), // В реальном приложении используйте более сложный пароль
            'role' => User::ROLE_VISITOR,
        ]);
    }
}
