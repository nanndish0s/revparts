<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        // Create a new test record
        $test = new Test();
        $test->name = 'MongoDB Test';
        $test->description = 'This is a test record to verify MongoDB connection';
        $test->save();

        // Retrieve all test records
        $tests = Test::all();

        return response()->json([
            'message' => 'Test record created successfully',
            'tests' => $tests
        ]);
    }
}
