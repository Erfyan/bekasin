<?php

namespace App\Controllers;

class Diagnostic extends BaseController
{
    public function index()
    {
        $results = [];
        
        // 1. Check PHP extensions
        $results['pgsql_ext'] = extension_loaded('pgsql') ? 'YES' : 'NO';
        $results['pdo_pgsql_ext'] = extension_loaded('pdo_pgsql') ? 'YES' : 'NO';
        $results['mysqli_ext'] = extension_loaded('mysqli') ? 'YES' : 'NO';
        $results['environment'] = ENVIRONMENT;
        $results['vercel_env'] = getenv('VERCEL') ?: 'not set';
        
        // 2. Test direct PDO connection
        try {
            $dsn = "pgsql:host=db.splspwbteapwnwaczxme.supabase.co;port=5432;dbname=postgres;sslmode=require";
            $pdo = new \PDO($dsn, 'postgres', '_6wuhahPzp_AF#w', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_TIMEOUT => 10,
            ]);
            $results['pdo_connection'] = 'SUCCESS';
            
            $stmt = $pdo->query("SELECT COUNT(*) FROM public.users");
            $results['users_count'] = $stmt->fetchColumn();
        } catch (\Exception $e) {
            $results['pdo_connection'] = 'FAILED: ' . $e->getMessage();
        }
        
        // 3. Test CI4 DB connection
        try {
            $db = \Config\Database::connect();
            $results['ci4_driver'] = $db->DBDriver ?? 'unknown';
            $results['ci4_hostname'] = $db->hostname ?? 'unknown';
            $results['ci4_port'] = $db->port ?? 'unknown';
            
            $query = $db->query("SELECT COUNT(*) as cnt FROM users");
            $row = $query->getRow();
            $results['ci4_connection'] = 'SUCCESS';
            $results['ci4_users_count'] = $row->cnt ?? 0;
        } catch (\Exception $e) {
            $results['ci4_connection'] = 'FAILED: ' . $e->getMessage();
        }
        
        // 4. Test login flow
        try {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->findByCredentials('admin');
            $results['find_admin'] = $user ? 'FOUND (id=' . $user['id'] . ')' : 'NOT FOUND';
            
            if ($user) {
                $results['password_verify'] = password_verify('admin123', $user['password_hash']) ? 'MATCH' : 'NO MATCH';
            }
        } catch (\Exception $e) {
            $results['find_admin'] = 'ERROR: ' . $e->getMessage();
        }
        
        header('Content-Type: application/json');
        echo json_encode($results, JSON_PRETTY_PRINT);
        exit;
    }
}
