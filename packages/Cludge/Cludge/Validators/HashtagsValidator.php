<?php

namespace App\Validators;

class HashtagsValidator
{

    /**
     * Validate if the organisation id exists and the user can use the organisation
     *
     * @param string $attribute  The attribute
     * @param string $value      The value
     * @param array  $parameters The parametres
     * @param mixed  $validator  The validator
     *
     * @return boolean
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        $tags = explode(',', $value);
        $valid = true;

        // Check the format
        foreach ($tags as $tag) {
            $tag = trim($tag);
            if ( ! preg_match('/^#[^#,]+/', $tag)) {
                $valid = false;
                break;
            }
        }

        // Check the max tags allowed
        if (count($tags) > (int) config('social.max_allowed_tags')) {
            $valid = false;
        }

        return $valid;
    }
}