<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Domain implements ValidationRule
{
    public $domainName;

    public function __construct($domainName)
    {
        $this->domainName = $domainName;
    }
    
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = $this->isValidURL($value, $this->domainName);

        if (!$domain) {
            $fail(__('lang.url_not_valid'))->translate([
                'attribute' => $attribute,
            ]);
        }
    }

    private function isValidURL($url, $searchfor){
        if (strpos($url, $searchfor) != false) {
            return true;
        } else {
            return false;
        }
    }
}
