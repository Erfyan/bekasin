<?php

namespace App\Services;

use Config\Services;
use Config\Supabase;

class StorageService
{
    protected Supabase $config;
    protected $client;

    public function __construct()
    {
        $this->config = config('Supabase');
        $this->client = Services::curlrequest([
            'timeout' => 15,
            'http_errors' => false,
        ]);
    }

    /**
     * Upload an image file to Supabase Storage (or local storage fallback in dev)
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @param string $bucket 'product-images' | 'avatars' | 'transaction-proofs'
     * @param string $folder e.g. 'products/1' or 'avatars/5'
     * @return string Public URL or relative path
     */
    public function upload($file, string $bucket, string $folder = ''): string
    {
        if (!$file->isValid() || $file->hasMoved()) {
            throw new \RuntimeException($file->getErrorString());
        }

        // Validate MIME type & Extension
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $mime = $file->getMimeType();
        if (!in_array($mime, $allowedMimes, true)) {
            throw new \InvalidArgumentException("Format file tidak didukung: {$mime}");
        }

        // Validate max size 5MB
        if ($file->getSizeByUnit('mb') > 5) {
            throw new \InvalidArgumentException('Ukuran gambar maksimal adalah 5MB.');
        }

        $extension = $file->getClientExtension() ?: 'webp';
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $filePath = trim($folder, '/') . '/' . $filename;

        // Check if Supabase credentials are configured
        if (!empty($this->config->url) && !str_contains($this->config->url, 'your-project-ref') && !empty($this->config->serviceKey)) {
            $supabaseUrl = rtrim($this->config->url, '/') . "/storage/v1/object/{$bucket}/{$filePath}";

            $response = $this->client->request('POST', $supabaseUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config->serviceKey,
                    'apikey'        => $this->config->serviceKey,
                    'Content-Type'  => $mime,
                    'x-upsert'      => 'true',
                ],
                'body' => file_get_contents($file->getTempName()),
            ]);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                return $this->getPublicUrl($bucket, $filePath);
            }

            log_message('error', 'Supabase upload failed: ' . $response->getBody());
        }

        // Fallback to local public uploads for offline/local development without Supabase keys
        $targetDir = FCPATH . "uploads/{$bucket}/" . trim($folder, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $file->move($targetDir, $filename);
        return base_url("uploads/{$bucket}/" . trim($folder, '/') . '/' . $filename);
    }

    /**
     * Get public URL for a storage object
     */
    public function getPublicUrl(string $bucket, string $filePath): string
    {
        if (!empty($this->config->url) && !str_contains($this->config->url, 'your-project-ref')) {
            return rtrim($this->config->url, '/') . "/storage/v1/object/public/{$bucket}/{$filePath}";
        }

        return base_url("uploads/{$bucket}/{$filePath}");
    }

    /**
     * Delete an object from storage
     */
    public function delete(string $bucket, string $filePath): bool
    {
        if (!empty($this->config->url) && !str_contains($this->config->url, 'your-project-ref') && !empty($this->config->serviceKey)) {
            $supabaseUrl = rtrim($this->config->url, '/') . "/storage/v1/object/{$bucket}/{$filePath}";
            $response = $this->client->request('DELETE', $supabaseUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config->serviceKey,
                    'apikey'        => $this->config->serviceKey,
                ],
            ]);
            return $response->getStatusCode() === 200;
        }

        // Local deletion
        $localPath = FCPATH . "uploads/{$bucket}/{$filePath}";
        if (file_exists($localPath)) {
            return unlink($localPath);
        }

        return true;
    }
}
