<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем администратора
        $this->call(AdminUserSeeder::class);
        
        // Создаем организаторов
        $this->call(OrganizerUserSeeder::class);
        
        // Создаем посетителей
        $this->call(VisitorUserSeeder::class);
        
        // Создаем мероприятия
        $this->call(EventSeeder::class);
        
        // Создаем новости
        $this->call(NewsSeeder::class);
    }
}
