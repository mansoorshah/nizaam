<?php

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$email]);
    }

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash']) && $user['is_active']) {
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false;
    }

    public function createUser($email, $password, $role = 'user')
    {
        return $this->create([
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'is_active' => true
        ]);
    }

    public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    private function updateLastLogin($userId)
    {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = ?";
        $this->db->execute($sql, [$userId]);
    }
}
