<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем всех организаторов
        $organizers = User::where('role', User::ROLE_ORGANIZER)->get();
        
        // Мероприятия для разных типов занятий
        $eventTypes = [
            'Лекция',
            'Практическое занятие',
            'Семинар',
            'Мастер-класс',
            'Консультация',
            'Экзамен',
            'Зачет',
            'Курсовая работа',
            'Дипломная работа',
            'Научное исследование'
        ];
        
        // Предметы/дисциплины
        $subjects = [
            'Математика',
            'Физика',
            'Химия',
            'Биология',
            'Информатика',
            'История',
            'Литература',
            'Иностранный язык',
            'Философия',
            'Экономика',
            'Психология',
            'Педагогика',
            'Право',
            'Медицина',
            'Инженерия'
        ];
        
        foreach ($organizers as $organizer) {
            for ($i = 1; $i <= 10; $i++) {
                // Генерируем реалистичные данные для мероприятия
                $subject = $subjects[array_rand($subjects)];
                $eventType = $eventTypes[array_rand($eventTypes)];
                
                // Генерируем дату в будущем (от 1 до 30 дней)
                $daysToAdd = rand(1, 30);
                $startTime = now()->addDays($daysToAdd)->addHours(rand(8, 18))->addMinutes(rand(0, 11) * 5); // Время с шагом 5 минут
                
                // Генерируем длительность (от 45 до 180 минут)
                $duration = rand(45, 180);
                
                // Генерируем максимальное количество участников (от 10 до 50)
                $maxAttendees = rand(10, 50);
                
                Event::create([
                    'name' => $eventType . ' по ' . $subject,
                    'description' => 'Подробное описание ' . mb_strtolower($eventType) . ' по дисциплине "' . $subject . '". В рамках занятия будут рассмотрены ключевые аспекты темы и практические задания для закрепления материала.',
                    'photo_path' => null, // Можно оставить пустым или использовать заглушку
                    'start_time' => $startTime,
                    'duration' => $duration,
                    'max_attendees' => $maxAttendees,
                    'organizer_id' => $organizer->id,
                    'is_approved' => true,
                    'is_completed' => false,
                ]);
            }
        }
    }
}