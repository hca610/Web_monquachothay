<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function showAllEmployer() {
        $employers = Employer::all();
        return $employers;
    }

    public function showRecruitment($employerId, $name) {
        
    }
}
