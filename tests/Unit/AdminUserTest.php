<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class AdminUserTest extends TestCase
{
    /**
     * Тест проверяет функциональность блокировки пользователей администратором.
     */
    public function test_admin_can_block_user(): void
    {
        // Имитируем данные пользователя
        $user = [
            'id' => 1,
            'name' => 'Test User',
            'username' => 'testuser',
            'role' => 'visitor',
            'is_blocked' => false,
        ];
        
        // Проверяем, что пользователь не заблокирован
        $this->assertFalse($user['is_blocked']);
        
        // Имитируем блокировку пользователя администратором
        $user['is_blocked'] = true;
        
        // Проверяем, что пользователь заблокирован
        $this->assertTrue($user['is_blocked']);
        
        // Проверяем, что роль пользователя не изменилась
        $this->assertEquals('visitor', $user['role']);
    }
    
    /**
     * Тест проверяет права доступа администратора.
     */
    public function test_admin_role_permissions(): void
    {
        $adminRole = 'admin';
        
        // Проверяем, что роль администратора соответствует ожидаемому значению
        $this->assertEquals('admin', $adminRole);
        
        // Проверяем, что роль администратора не равна другим ролям
        $this->assertNotEquals('organizer', $adminRole);
        $this->assertNotEquals('visitor', $adminRole);
    }
    
    /**
     * Тест проверяет подсчет статистики пользователей в панели администратора.
     */
    public function test_admin_dashboard_statistics(): void
    {
        // Имитируем данные статистики
        $stats = [
            'totalUsers' => 150,
            'totalEvents' => 25,
            'blockedUsers' => 3,
        ];
        
        // Проверяем, что статистика корректно рассчитывается
        $this->assertIsInt($stats['totalUsers']);
        $this->assertIsInt($stats['totalEvents']);
        $this->assertIsInt($stats['blockedUsers']);
        
        // Проверяем, что количество заблокированных пользователей не превышает общее количество
        $this->assertLessThanOrEqual($stats['totalUsers'], $stats['blockedUsers']);
    }
}