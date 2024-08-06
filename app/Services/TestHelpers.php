<?php

namespace App\Services;

use Illuminate\Http\Request;

class TestHelpers
{

    protected Request $request;

    public function getHeadersXsrfToken(array $headers = []): array
    {
        $csrfCookie = $this->request->get('/sanctum/csrf-cookie');

        $tokenValue = $csrfCookie->getCookie('XSRF-TOKEN', false)->getValue();

        $headers['X-XSRF-TOKEN'] = $tokenValue;

        return $headers;
    }
}
