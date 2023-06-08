<?php

namespace Tests;

use Illuminate\Database\SQLiteConnection;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public $connection = '';

}
