<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Http\Requests\V1\WorkerRequest;
use App\Http\Resources\V1\WorkerResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        return WorkerResource::collection(Worker::latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkerRequest $request)
    {
        $request->validated();
        $last_name=$request->input('last_name');
        $emailBeforeSave='';
        $letter='';
        for($i=0;$i<strlen($last_name);$i++){
            $letter.=$last_name[$i];
            $email=$request->input('name').$letter.'@test.com';
            $userVerif=User::where('email',$email)->first();
            if (!isset($userVerif)) {
             $emailBeforeSave=$email;
             break;
         }             
     } 

     try {
         $user = User::create([
            'name' => $request->input('name'),
            'last_name'=>$last_name,
            'email'=>strtolower($emailBeforeSave),
            'password'=>Hash::make($request->input('dni'))
        ]);
         $url_image = $this->upload($request->file('photo'));

         $user->worker()->create([
           'name' => $request->input('name'),
           'last_name'=>$last_name,
           'dni'=>$request->input('dni'),
           'birthday'=>$request->input('birthday'),
           'photo'=>$url_image,
       ]);

         $user->worker->jobs()->sync($request->input('jobs'));
         return response()->json(['message' => 'Worker create succesfully'], 201);
     } catch (Exception $e) {
        return response()->json(['message' => $e], 500);
    }

    
}

private function upload($image)
{
    $path_info = pathinfo($image->getClientOriginalName());
    $post_path = 'images/workers';

    $rename = uniqid() . '.' . $path_info['extension'];
    $image->move(public_path() . "/$post_path", $rename);
    return "$post_path/$rename";
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function show(Worker $worker)
    {
        return new WorkerResource($worker);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Worker $worker)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:70',
            'last_name' => 'required|max:70',
            'dni' => 'required|max:25',
            'jobs' => 'required',
        ])->validate();

        $user=User::findOrFail($worker->user_id);

        if (!empty($request->input('name'))) {
            $user->name=$request->input('name');
            $worker->name = $request->input('name');
        }
        if (!empty($request->input('last_name'))) {
            $user->last_name=$request->input('last_name');
            $worker->last_name = $request->input('last_name');
        }
        $user->save();
        if (!empty($request->file('image'))) {
            Storage::delete('public/'.$worker->photo);
            $url_image = $this->upload($request->file('photo'));
            $worker->photo = $url_image;
        }
        if (!empty($request->input('dni'))) {
            $worker->dni = $request->input('dni');
        }
        if (!empty($request->input('jobs'))) {
            $worker->jobs()->sync($request->input('jobs'));
        }

        $res = $worker->save();

        if ($res) {
            return response()->json(['message' => 'Work update succesfully']);
        }

        return response()->json(['message' => 'Error to update post'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Worker $worker)
    {
        Storage::delete('public/'.$worker->photo);
        $user=User::find($worker->user_id);
        $res = $user->delete();
        if ($res) {
            return response()->json(['message' => 'Worker delete succesfully']);
        }

        return response()->json(['message' => 'Error to update post'], 500);
    }
}
