<?php

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

class CreateSeasonValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "number" => [
                Rule::unique("seasons")->where("film_id", (int) $this->arg("film_id"))
            ]
        ];
    }
}
