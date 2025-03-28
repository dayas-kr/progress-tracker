<?php

namespace App\Helpers\Traits;

trait ConvertsJson
{
    public function convertJsonToObject($data)
    {
        // First decode: returns an array
        $decoded = json_decode($data, true);

        // Check if it's still a JSON string (i.e., double-encoded)
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        // Convert array to object
        return json_decode(json_encode($decoded));
    }
}
