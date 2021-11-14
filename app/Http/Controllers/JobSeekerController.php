<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use App\Models\User;
use Illuminate\Http\Request;

class JobSeekerController extends Controller
{
    public function index()
    {
        $jobSeekers = JobSeeker::paginate(20);
        return $jobSeekers;
    }

    public function create()
    {
        // return view('jobseeker.create');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user_id = $user->save();

        $jobSeeker = new JobSeeker();
        $jobSeeker->fill($request->all());
        $jobSeeker->user_id = $user_id;
        $jobSeeker->save();
    }

    public function show($id)
    {
        return view('jobseeker.show');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $jobSeeker = Employer::findOrFail($id);
        $jobSeeker->fill($request->all());
        $jobSeeker->save();
    }

    public function destroy($id)
    {
        //
    }

    public function followRecruitment($recruitmentId)
    {
        $this->recruitments->pivot->job_seeker_id = $this->job_seeker_id;
        $this->recruitments->pivot->recruitment_id = $recruitmentId;
        $this->recruitments->pivot->type = 'following';
        $this->recruitments->pivot->save();
    }
}
