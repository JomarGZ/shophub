<?php

return [
    'url_protocols' => explode(',', env('PAYMENT_URL_PROTOCOLS', 'https')),
];
