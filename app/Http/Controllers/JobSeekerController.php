<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use App\Models\User;
use Illuminate\Http\Request;

class JobSeekerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobSeekers = JobSeeker::all();
        return view('jobseeker.index')->with('jobseekers', $jobSeekers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jobseeker.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('jobseeker.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $jobSeeker = Employer::findOrFail($id);
        $jobSeeker->fill($request->all());
        $jobSeeker->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
