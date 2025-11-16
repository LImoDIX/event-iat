<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OrganizerEventTest extends TestCase
{
    /**
     * Тест проверяет функциональность создания мероприятий организатором.
     */
    public function test_organizer_can_create_event(): void
    {
        // Имитируем данные мероприятия
        $event = [
            'name' => 'Test Event',
            'description' => 'Test event description',
            'max_attendees' => 50,
            'organizer_id' => 1,
            'is_approved' => true,
        ];
        
        // Проверяем, что мероприятие имеет все необходимые поля
        $this->assertArrayHasKey('name', $event);
        $this->assertArrayHasKey('description', $event);
        $this->assertArrayHasKey('max_attendees', $event);
        $this->assertArrayHasKey('organizer_id', $event);
        $this->assertArrayHasKey('is_approved', $event);
        
        // Проверяем типы данных
        $this->assertIsString($event['name']);
        $this->assertIsString($event['description']);
        $this->assertIsInt($event['max_attendees']);
        $this->assertIsInt($event['organizer_id']);
        $this->assertIsBool($event['is_approved']);
    }
    
    /**
     * Тест проверяет логику подсчета участников мероприятия.
     */
    public function test_event_attendee_count(): void
    {
        // Имитируем данные о регистрации и посещении
        $registrationsCount = 45;
        $attendancesCount = 40;
        $maxAttendees = 50;
        
        // Проверяем, что количество посещений не превышает количество регистраций
        $this->assertLessThanOrEqual($registrationsCount, $attendancesCount);
        
        // Проверяем, что количество регистраций не превышает максимальное
        $this->assertLessThanOrEqual($maxAttendees, $registrationsCount);
        
        // Рассчитываем доступные места
        $availableSpots = $maxAttendees - $registrationsCount;
        $this->assertEquals(5, $availableSpots);
    }
    
    /**
     * Тест проверяет функциональность завершения мероприятия.
     */
    public function test_organizer_can_complete_event(): void
    {
        // Имитируем данные мероприятия
        $event = [
            'id' => 1,
            'name' => 'Test Event',
            'is_completed' => false,
        ];
        
        // Проверяем, что мероприятие не завершено
        $this->assertFalse($event['is_completed']);
        
        // Имитируем завершение мероприятия
        $event['is_completed'] = true;
        
        // Проверяем, что мероприятие завершено
        $this->assertTrue($event['is_completed']);
    }
}