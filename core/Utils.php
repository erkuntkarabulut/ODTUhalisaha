<?php

namespace core;

class Utils
{
    /**
     * Gerçek istemci IP adresini döndürür.
     * Proxy veya CDN arkasında çalışıyorsa, X-Forwarded-For başlıklarını kontrol eder.
     * @return string İstemcinin gerçek IP adresi
     */
    public static function getRealIP()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ipList[0]); // İlk IP genellikle istemci IP’sidir.
        }
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Tarih formatını uygun hale getirir (Örn: 2025-02-21 → 21 Şubat 2025)
     * @param string $dateString - YYYY-MM-DD formatındaki tarih
     * @return string Okunaklı tarih formatı
     */
    public static function formatDate($dateString)
    {
        return date("d F Y", strtotime($dateString));
    }

    /**
     * Sayının belirli bir ondalık basamakla formatlanmasını sağlar.
     * @param float $amount - Formatlanacak sayı
     * @param int $decimals - Ondalık basamak sayısı
     * @return string Formatlanmış sayı
     */
    public static function formatMoney($amount, $decimals = 2)
    {
        return number_format($amount, $decimals, ',', '.') . ' ₺';
    }
}
