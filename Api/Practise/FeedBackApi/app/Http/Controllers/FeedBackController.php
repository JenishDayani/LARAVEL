<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeedBack;

class FeedBackController extends Controller
{
    public function store(Request $req)
    {
        $feedback = new FeedBack;
        $feedback->name = $req->input('Name');
        $feedback->city = $req->input('City');
        $feedback->phone = $req->input('Phone');
        $feedback->email = $req->input('Email');
        $feedback->sFriendly = $req->input('StaffFriendly');
        $feedback->sAnswer = $req->input('StaffAnswer');
        $feedback->sResolve = $req->input('StaffResolve');
        $feedback->rate = $req->input('Rate');
        $feedback->recommend = $req->input('Recommend');
        $feedback->quality = $req->input('Quality');
        $feedback->question = $req->input('Question');
        $feedback->save();
        return response()->json([
            "message" => "Feedback Added Successfully"
        ]);
    }

    public function view()
    {
        $feedback = FeedBack::all();
        return response()->json($feedback);
    }

    public function viewAscending()
    {
        $data = Feedback::orderBy('created_at','asc')->get();
        return response()->json($data);

    }

    public function viewDescending()
    {
        $data = Feedback::orderBy('created_at','desc')->get();
        return response()->json($data);
    }
}
