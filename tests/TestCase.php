<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    // Set to true to tell the tests that they need to seed the database
    // Useful to get a default user to authenticate as and to get the default tasks
    protected $seed = true;
}
