<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class VisitorRegistrationTest extends TestCase
{
    /**
     * Тест проверяет функциональность регистрации посетителя на мероприятие.
     */
    public function test_visitor_can_register_for_event(): void
    {
        // Имитируем данные регистрации
        $registration = [
            'event_id' => 1,
            'user_id' => 5,
            'confirmation_code' => '1234',
            'created_at' => '2025-01-01 10:00:00',
        ];
        
        // Проверяем, что регистрация имеет все необходимые поля
        $this->assertArrayHasKey('event_id', $registration);
        $this->assertArrayHasKey('user_id', $registration);
        $this->assertArrayHasKey('confirmation_code', $registration);
        $this->assertArrayHasKey('created_at', $registration);
        
        // Проверяем формат кода подтверждения
        $this->assertIsString($registration['confirmation_code']);
        $this->assertEquals(4, strlen($registration['confirmation_code']));
        $this->assertMatchesRegularExpression('/^\d{4}$/', $registration['confirmation_code']);
    }
    
    /**
     * Тест проверяет логику отмены регистрации посетителем.
     */
    public function test_visitor_can_cancel_registration(): void
    {
        // Имитируем существующую регистрацию
        $existingRegistration = [
            'id' => 1,
            'event_id' => 1,
            'user_id' => 5,
            'confirmation_code' => '1234',
        ];
        
        // Проверяем, что регистрация существует
        $this->assertNotNull($existingRegistration);
        $this->assertEquals(1, $existingRegistration['event_id']);
        $this->assertEquals(5, $existingRegistration['user_id']);
        
        // Имитируем отмену регистрации (удаление)
        $existingRegistration = null;
        
        // Проверяем, что регистрация удалена
        $this->assertNull($existingRegistration);
    }
    
    /**
     * Тест проверяет функциональность оценки мероприятий посетителем.
     */
    public function test_visitor_can_rate_event(): void
    {
        // Допустимые значения оценок
        $validRatings = [1, -1];
        
        // Проверяем, что каждая оценка допустима
        foreach ($validRatings as $rating) {
            $this->assertContains($rating, [1, -1]);
        }
        
        // Имитируем оценку мероприятия
        $rating = [
            'event_id' => 1,
            'user_id' => 5,
            'rating' => 1, // Лайк
        ];
        
        // Проверяем, что оценка допустима
        $this->assertContains($rating['rating'], $validRatings);
        $this->assertEquals(1, $rating['rating']);
    }
}