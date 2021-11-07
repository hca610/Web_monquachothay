<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function listEmployer() {
        $employers = Employer::all();
        return $employers;
    }
}
