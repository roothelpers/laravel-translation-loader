<?php
namespace App\Http\Controllers;

class ConfigController extends Controller
{
    public function index()
    {
      return view("config.index");
    }
    public function clearRoute()
    {
        \Artisan::call('cache:clear');
    }
}
