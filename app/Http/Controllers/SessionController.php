<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolSession;
use App\Models\AttendanceRecord;
use App\Http\Resources\SchoolSessionRessource;
use Carbon\Carbon;


class SessionController extends Controller
{

    public function index(){

        $sessions = SchoolSession::where('user_id', auth()->user()->id)
        ->withCount('attendanceRecords') // Adds a `attendance_records_count` attribute
        ->orderBy('date', 'DESC')
        ->paginate(7);

        return $sessions;
    }

    public function create(Request $request){
        $request->validate(
            ['intitule'=>'required|string|max:255',
            'date'=>'required|date']
        );

        $school_session = SchoolSession::create(
            ['intitule'=>$request->intitule,
            'date'=>date('Y-m-d', strtotime($request->date)),
            'period'=>0, //Disabled for now
            'user_id'=>auth()->user()->id]
        );

        if($school_session){
            return response()->json(['message'=>"School Session Created", 'id'=>$school_session->id],200);
        }else{
            return response()->json(['message'=>"Failed to Create School Session"],500);
        }

    }

    public function register(Request $request){
        $request->validate([
            'id'=>'required|exists:school_sessions'
        ]);

        $rows_count = AttendanceRecord::where('user_id',auth()->user()->id)
        ->where('school_sessions_id',$request->id)
        ->count();

        if($rows_count!= 0){
            return response()->json(["message"=>"Étudiant déjà inscrit à cette session"],400);
        }

        $record = AttendanceRecord::create([
            'school_sessions_id'=>$request->id,
            'user_id'=>auth()->user()->id,
            'timestamp'=>Carbon::now()
        ]);

        $session = SchoolSession::where('id',$request->id)->first();

        return response()->json([
            "message"=>"Inscription réussie",
            "intitule"=>$session->intitule,
            "date"=>$session->date,
            "enseignant"=>$session->user()->first()->nom." ".$session->user()->first()->prenom,
            ],200);
    }
}
