<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobSeekerController extends Controller
{
    public function search(Request $request)
    {
        $jobSeekers = DB::table('job_seekers')
            ->join('users', 'users.user_id', '=', 'job_seekers.user_id')
            ->where('birthday', 'like', "%$request->birthday%")
            ->where('qualification', 'like', "%$request->qualification%")
            ->where('work_experience', 'like', "% $request->work_experience%")
            ->where('education', 'like', "%$request->education%")
            ->where('skill', 'like', "%$request->skill%")
            ->where('name', 'like', "%$request->name%")
            ->where('username', 'like', "%$request->username%")
            ->where('phonenumber', 'like', "%$request->phonenumber%")
            ->where('email', 'like', "%$request->email%")
            ->where('address', 'like', "%$request->address%")
            ->where('status', 'like', "%$request->status%")
            ->paginate(20);

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
        $user->role = 'jobseeker';
        $user_id = $user->save();

        $jobSeeker = new JobSeeker();
        $jobSeeker->fill($request->all());
        $jobSeeker->user_id = $user_id;
        $jobSeeker->save();
    }

    public function show($id)
    {
        $jobSeeker = JobSeeker::find($id);
        if ($jobSeeker != NULL) {
            return DB::table('job_seekers')
                ->join('users', 'users.user_id', '=', 'job_seekers.user_id')
                ->where('job_seeker_id', '=', $id)
                ->get();
        } else {
            return 'Not found';
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $jobSeeker = Employer::find($id);
        $jobSeeker->fill($request->all());
        $jobSeeker->save();

        $user = User::find($jobSeeker->user_id);
        $user->fill($request->all());
        $user->save();
    }

    public function destroy($id)
    {
        //
    }

    public function followRecruitment(Request $request)
    {
        $this->recruitments->pivot->job_seeker_id = $this->job_seeker_id;
        $this->recruitments->pivot->recruitment_id = $request->recruitment_id;
        $this->recruitments->pivot->type = 'following';
        $this->recruitments->pivot->save();
    }
}
