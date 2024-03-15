<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaveMessageController extends Controller
{

  public function index(Request $request) {
    print_r($request);exit;
  }

  public function save(Request $request){
    
    $message_data = array(
      'id' => $request->id,
      'text' => $request->text,
      'updated_by' => $request->updated_by,
      'started_at' => $request->started_at,
      'finished_at' => $request->finished_at,
      'created_at' => $request->created_at,
      'updated_at' => $request->updated_at,
    );
    // dd($request);
    DB::table('messages')->insert($message_data);
    
    // return redirect('/dashboard');
    
  }

  public function edit(Request $request)
  {
    $id = $request->id;
    $message_data = array(
      'text' => $request->message,
      'updated_by' => $request->writer,
      'started_at' => $request->start,
      'finished_at' => $request->finish,
      'created_at' => $request->create,
      'updated_at' => Carbon::now()
    );
    DB::table('messages')->where('id', $id)->update($message_data);
    
    // return redirect('/dashboard');
  }

  public function delete(Request $request)
  {
    $id = $request->id;
    $message_data = array(
      'text' => $request->message,
      'updated_by' => $request->writer,
      'started_at' => $request->start,
      'finished_at' => $request->finish,
      'created_at' => $request->start,
      'updated_at' => Carbon::now(),
      'deleted_at' => Carbon::now()
    );
    DB::table('messages')->where('id', $id)->update($message_data);
    
    // return redirect('/dashboard');
  }

}