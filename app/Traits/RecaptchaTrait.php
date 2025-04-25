<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait RecaptchaTrait
{
    public function validateRecaptcha(Request $request)
    {
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ]));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $responseKeys = json_decode($response, true);

        return isset($responseKeys["success"]) && $responseKeys["success"] === true;
    }
}
