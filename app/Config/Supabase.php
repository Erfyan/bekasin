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
     * Supabase Keys & Options
     */
    public string $publishableKey = '';
    public string $secretKey = '';
    public string $jwksUrl = '';
    public string $anonKey = '';
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

        $this->url = env('SUPABASE_URL', 'https://splspwbteapwnwaczxme.supabase.co');
        $this->publishableKey = env('SUPABASE_PUBLISHABLE_KEY', 'sb_publishable_OnWN8ytImmo1VmXepf4fog_YTJjyfQu');
        $this->secretKey = env('SUPABASE_SECRET_KEY', '');
        $this->jwksUrl = env('SUPABASE_JWKS_URL', 'https://splspwbteapwnwaczxme.supabase.co/auth/v1/.well-known/jwks.json');

        // Fallbacks
        $this->anonKey = env('SUPABASE_ANON_KEY', $this->publishableKey);
        $this->serviceKey = env('SUPABASE_SERVICE_KEY', $this->secretKey);

        $this->bucketProducts = env('SUPABASE_STORAGE_BUCKET_PRODUCTS', 'product-images');
        $this->bucketAvatars = env('SUPABASE_STORAGE_BUCKET_AVATARS', 'avatars');
        $this->bucketTransactions = env('SUPABASE_STORAGE_BUCKET_TRANSACTIONS', 'transaction-proofs');
    }
}
