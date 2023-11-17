<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Here the list all of the questions
        $questions = Question::all();

        return response()->json($questions);
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
    public function store(Request $request)
    {
        // Store question in to database
        $question = Question::create([
            'user_id' => Auth::id(),
            'value' => $request->value,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $question = Question::findOrFail($id);
        
            return response()->json([
                'question' => $question,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'not found question ..',
            ], 404);
        }
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Successfully deleted',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Error deleted',
            ], 404);
        }
    }
}
