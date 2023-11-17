<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Vioce;
use App\Models\Voice;
use App\Repositories\VoiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PermissionException;
use App\Http\Requests\VoiceRequest;
use Illuminate\Validation\ValidationException;

class VoiceController extends Controller
{
    private $voice;
    public function __construct(VoiceRepository $voice)
    {
        $this->voice = $voice;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoiceRequest $request)
    {
        try {
            $result = $this->voice->checkStoreVoices([
                'question_id' => $request->question_id,
                'value' => $request->value
            ]);
    
            if ($result == "createdNew") {
                Voice::create([
                    'user_id'=> Auth::id(),
                    'question_id' => $request->question_id,
                    'value'=> $request->value
                ]);
                return response()->json([
                    'status'=>200,
                    'message'=>'Voting completed successfully'
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Voice $voice)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoiceRequest $request)
    {
        try {
            $result = $this->voice->checkUpdatedVoices([
                'question_id' => $request->question_id,
                'value' => $request->value
            ]);
            $voice = Voice::where('user_id', Auth::id())->where('question_id', $request->question_id)->first();
            if ($result == 'updatedVoice') {
                $voice->update([
                    'value'=>$request->value
                ]);
                return response()->json([
                    'status'=>200,
                    'message'=>'Successfully updated your voice'
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
