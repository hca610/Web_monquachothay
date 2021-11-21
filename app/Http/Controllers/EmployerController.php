<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    public function showRecruitments($employerId)
    {
        try {
            $employer = Employer::findOrFail($employerId);
            return $employer->recruitments;
        } catch (Exception $e) {
            
        }
    }
}
