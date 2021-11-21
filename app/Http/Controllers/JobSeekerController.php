<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobSeekerController extends Controller
{
    // TODO: them phan  follow, cong viec cac thu
   public function followRecruitment(Request $request)
    {
        $this->recruitments->pivot->job_seeker_id = $this->job_seeker_id;
        $this->recruitments->pivot->recruitment_id = $request->recruitment_id;
        $this->recruitments->pivot->type = 'following';
        $this->recruitments->pivot->save();
    }
}
