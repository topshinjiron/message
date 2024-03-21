<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ApiController extends Controller
{
    public function publicMessage()
    {
        $currentDateTime = Carbon::now('Asia/Tokyo');
        $all_messages = DB::table('messages')->whereNull('deleted_at')->orderBy('updated_at', 'desc')->get();

        foreach($all_messages as $key => $all_message)
        {
            // Convert $all_message->started_at to a Carbon instance if needed
            $started_at = Carbon::parse($all_message->started_at, 'Asia/Tokyo');
            $finished_at = Carbon::parse($all_message->finished_at, 'Asia/Tokyo');
    
            if(!($currentDateTime->gte($started_at) && $currentDateTime->lte($finished_at)))
            {
                unset($all_messages[$key]);
            }
        }
        
        return response()->json(['message' => $all_messages]);
    }

    // public function estimateMessage()
    // {
    //     return response()->json(['message' => 'This is the estimate message.']);
    // }

    // public function finishedMessage()
    // {
    //     return response()->json(['message' => 'This is the finished message.']);
    // }

    // public function allMessage()
    // {
    //     $all_messages = DB::table('messages')->whereNull('deleted_at')->orderBy('updated_at', 'desc')->get();
    //     return response()->json(['message' => $all_messages]);
    // }
}