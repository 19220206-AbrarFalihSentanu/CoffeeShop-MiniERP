<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    /**
     * Available languages
     */
    protected $languages = [
        'id' => [
            'name' => 'Indonesia',
            'native' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇺🇸',
        ],
    ];

    /**
     * Switch language
     */
    public function switch(Request $request, string $locale)
    {
        // Validate locale
        if (!array_key_exists($locale, $this->languages)) {
            $locale = config('app.locale', 'id');
        }

        // Store in session
        Session::put('locale', $locale);

        // Set application locale
        App::setLocale($locale);

        // Create cookie that lasts 1 year
        $cookie = Cookie::make('locale', $locale, 60 * 24 * 365);

        // Redirect back with cookie
        return redirect()->back()->withCookie($cookie);
    }

    /**
     * Get current locale
     */
    public function current()
    {
        return response()->json([
            'locale' => App::getLocale(),
            'language' => $this->languages[App::getLocale()] ?? $this->languages['id'],
        ]);
    }

    /**
     * Get all available languages
     */
    public function available()
    {
        return response()->json([
            'current' => App::getLocale(),
            'languages' => $this->languages,
        ]);
    }
}
