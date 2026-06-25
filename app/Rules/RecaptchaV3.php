<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class RecaptchaV3 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        $body = $response->json();

        // Check if the validation failed or if the score is too low (e.g. below 0.5)
        if (!isset($body['success']) || !$body['success'] || (isset($body['score']) && $body['score'] < 0.5)) {
            $fail('The Google reCAPTCHA verification failed. Please try again.');
        }
    }
}
