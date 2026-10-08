<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Supabase extends BaseConfig
{
    /**
     * Supabase Project URL
     */
    public string $url = '';

    /**
     * Supabase Anon Public Key (Safe for client, if needed)
     */
    public string $anonKey = '';

    /**
     * Supabase Service Role Key (SERVER-SIDE ONLY, NEVER EXPOSE TO FRONTEND)
     */
    public string $serviceKey = '';

    /**
     * Supabase Storage Buckets
     */
    public string $bucketProducts = 'product-images';
    public string $bucketAvatars = 'avatars';
    public string $bucketTransactions = 'transaction-proofs';

    public function __construct()
    {
        parent::__construct();

        $this->url = env('SUPABASE_URL', 'https://your-project-ref.supabase.co');
        $this->anonKey = env('SUPABASE_ANON_KEY', '');
        $this->serviceKey = env('SUPABASE_SERVICE_KEY', '');
        $this->bucketProducts = env('SUPABASE_STORAGE_BUCKET_PRODUCTS', 'product-images');
        $this->bucketAvatars = env('SUPABASE_STORAGE_BUCKET_AVATARS', 'avatars');
        $this->bucketTransactions = env('SUPABASE_STORAGE_BUCKET_TRANSACTIONS', 'transaction-proofs');
    }
}
