<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/'
        ]);

        $response = $client->post('siteverify', [
            'query' => [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
            ]
        ]);

        return json_decode($response->getBody())->success;
    }

    public function message()
    {
        return "خطای پذیرش من ربات نیستم";
    }
}
