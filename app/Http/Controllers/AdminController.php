<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Faculty;
use App\Department;
use App\Position;
use App\User;
use App\Voting;
use App\Candidate;
use App\Employee;
use App\AdmittedVoter;

use App\Events\VotingFilled;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'checkAdminRole']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.index', [
            'users' => User::paginate(10),
            'faculties' => Faculty::all(),
            'departments' => Department::all(),
            'positions' => Position::all(),
            'votings' => Voting::all()
        ]);
    }

    public function clients()
    {

        return view('admin.clients');
    }

    public function changeUser(Request $request, int $userId)
    {

        $user = User::findOrFail($userId);
        $employee = $user->employee;
        if ($request->has('department') &&
                $request->input('department') !== 'none' &&
                $request->has('position') &&
                $request->input('position') !== 'none'
        ) {
            if (empty($employee)) {
                $employee = new Employee();
                $employee->user_id = $user->id;
            }
            $department = Department::findOrFail($request->input('department'));
            $position = Position::findOrFail($request->input('position'));
            $employee->department_id = $department->id;
            $employee->position_id = $position->id;
            $employee->save();
        }

        return redirect()->route('admin::index');
    }

    public function createVotingForm()
    {

        return view('admin.create_voting_form', [
            'faculties' => Faculty::all(),
            'departments' => Department::all(),
            'positions' => Position::all()
        ]);
    }

    public function createVoting(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
            'start_time' => 'required|date|after:today',
            'end_time' => 'required|date|after:start_time'
        ]);

        $voting = new Voting();
        $voting->name = $request->input('name');
		$voting->description = $request->input('description');
		$voting->start_time = $request->input('start_time');
		$voting->end_time = $request->input('end_time');

        if ($request->has('faculty')) {
            if($request->input('faculty') !== 'none') {
                $faculty = Faculty::findOrFail($request->input('faculty'));
                $faculty->votings()->save($voting);
            }
        }
        if ($request->has('department')) {
            if($request->input('department') !== 'none') {
                $department = Department::findOrFail($request->input('department'));
                $department->votings()->save($voting);
            }
        }
        if ($request->has('position')) {
            if($request->input('position') !== 'none') {
                $position = Position::findOrFail($request->input('position'));
                $position->votings()->save($voting);
            }
        }

        $voting->save();

        return redirect()->route('admin::index');
    }

    public function voting(Request $request, int $votingId)
    {

        return view('admin.voting', [
            'voting' => Voting::findOrFail($votingId)
        ]);
    }

    public function addCandidate(Request $request, int $votingId)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);

        $candidate = new Candidate();

        $voting = Voting::findOrFail($votingId);
        if($voting->is_published) {
            return redirect()->route('admin::voting', ['votingId' => $votingId]);
        }
        $candidate->name = $request->input('name');
        $candidate->description = $request->input('description');
        $voting->candidates()->save($candidate);
        $candidate->save();
        
        return redirect()->route('admin::voting', ['votingId' => $votingId]);
    }

    public function deleteCandidate(Request $request, int $candidateId)
    {

        $candidate = Candidate::findOrFail($candidateId);

        if($candidate->voting->is_published) {
            return redirect()->route('admin::voting', ['votingId' => $votingId]);
        }

        $votingId = $candidate->voting->id;
        $candidate->delete();

        return redirect()->route('admin::voting', ['votingId' => $votingId]);
    }

    public function publishVoting(Request $request, int $votingId)
    {

        $voting = Voting::findOrFail($votingId);
        if($voting->is_published) {
            return redirect()->route('admin::voting', ['votingId' => $votingId]);
        }

        if( ! empty($voting->department->id)) {
            $voters = Employee::where('department_id', $voting->department->id)->get();
        } else {
            $departments = array_map(function($d){
                return $d->id;
            }, $voting->faculty->departments);
            $voters = Employee::whereIn('department_id', $departments)->get();
        }
        
        foreach($voters as $voter) {
            $admVoter = new AdmittedVoter();
            $admVoter->user_id = $voter->user_id;
            $admVoter->voting_id = $voting->id;
            $admVoter->save();
        }

        $voting->is_published = false;
        $voting->save();

        //асинхронная публикация на блокчейн-сервер
        event(new VotingFilled($voting));

        return redirect()->route('admin::voting', ['votingId' => $votingId]);
    }

    public function deleteVoting(Request $request, int $votingId)
    {

        $voting = Voting::findOrFail($votingId);

        if($voting->is_published) {
            return redirect()->route('admin::index');
        }

        $voting->delete();

        return redirect()->route('admin::index');
    }
}
