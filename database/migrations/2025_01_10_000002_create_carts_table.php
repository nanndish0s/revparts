<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCartsTable extends Migration
{
    public function up()
    {
        DB::connection('mongodb')->collection('carts')->create([
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => ['user_id', 'product_id', 'quantity'],
                    'properties' => [
                        'user_id' => [
                            'bsonType' => 'objectId',
                            'description' => 'must be an ObjectId and is required'
                        ],
                        'product_id' => [
                            'bsonType' => 'objectId',
                            'description' => 'must be an ObjectId and is required'
                        ],
                        'quantity' => [
                            'bsonType' => 'int',
                            'minimum' => 1,
                            'description' => 'must be an integer >= 1 and is required'
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function down()
    {
        DB::connection('mongodb')->collection('carts')->drop();
    }
}
