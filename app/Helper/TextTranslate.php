<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

/**
 * Function to translate HTML content while preserving tags.
 *
 * @param string $html The HTML content to translate.
 * @param string $targetLang The target language code.
 * @return string The translated HTML content.
 */
function translateWithHTMLTags($html, $targetLang = null)
{
    // Return original content if empty or null
    if (empty($html) || is_null($html)) {
        return $html ?? '';
    }

    $targetLang = $targetLang ?? App::getLocale();
    
    // Create cache key based on content and target language
    $cacheKey = 'translation_' . md5($html . $targetLang);
    
    // Try to get from cache first (cache for 24 hours)
    $cached = Cache::get($cacheKey);
    if ($cached !== null) {
        return $cached;
    }

    try {
        $translate = new GoogleTranslate($targetLang);
        
        // Set timeout and retry options
        $translate->setOptions([
            'timeout' => 10,
            'connect_timeout' => 5,
            'verify' => false, // Disable SSL verification if needed
        ]);

        if (strip_tags($html) !== $html) {
            $translatedHtml = preg_replace_callback(
                '/>([^<]+)</',
                function ($matches) use ($translate) {
                    try {
                        $text = $matches[1];
                        if (trim($text) === '') {
                            return $matches[0];
                        }
                        $translatedText = $translate->translate(trim($text));
                        return '>' . $translatedText . '<';
                    } catch (\Exception $e) {
                        // Return original text if translation fails
                        return $matches[0];
                    }
                },
                $html
            );
        } else {
            $translatedHtml = $translate->translate($html);
        }

        // Cache the successful translation
        Cache::put($cacheKey, $translatedHtml, now()->addHours(24));
        
        return $translatedHtml;
        
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::warning('Translation failed: ' . $e->getMessage(), [
            'target_lang' => $targetLang,
            'content_preview' => substr($html, 0, 100),
            'exception_class' => get_class($e)
        ]);
        
        // Return original content as fallback
        return $html;
    }
}
