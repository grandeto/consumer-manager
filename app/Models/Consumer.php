<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Consumer extends Model
{
    use SoftDeletes;

    protected $table = 'consumers';
    public $timestamps = true;

    protected $fillable = [
        'name', 'age', 'city'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get allowed cities.
     *
     * @return Array
     */
    public static function getAllowedCities()
    {
        return ['Sofia', 'Plovdiv', 'Varna',];
    }

    /**
     * Validate consumers operations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $rule
     * @return \Illuminate\Support\Facades\Validator
     */
    public static function getValidator($data, $rule = 'default')
    {
        $rules = [
            'default' => [
                'name' => 'required|string|min:4|max:40',
                'age' => 'required|numeric|min:14|max:99',
                'city' => [
                    'required',
                    'string',
                    Rule::in(Self::getAllowedCities()),
                ]
            ],
        ];

        if (!isset($rules[$rule])) $rule = 'default';

        return Validator::make($data, $rules[$rule]);
    }
}
