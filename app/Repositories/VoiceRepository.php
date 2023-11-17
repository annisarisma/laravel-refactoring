<?php

namespace App\Repositories;

use App\Models\Question;
use App\Models\Voice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VoiceRepository
{

    /**
     * Get all faculties
     *
     * @return void
     */
    public function checkStoreVoices(array $data)
    {
        $question = Question::where('id', $data['question_id'])->where('user_id', '!=', Auth::id())->first();
        
        if ($question) {
            // If not question owner
            $question_user = Question::where('id', $data['question_id'])->whereHas('voice', function ($query_voice) {
                $query_voice->where('user_id', Auth::id());
            })->first();

            if ($question_user) {
                // if have voice before
                throw ValidationException::withMessages(['You have already create voice, please update']);
            } else {
                // if doesnt have voice before
                return 'createdNew';
            }
        } else {
            throw ValidationException::withMessages(['You cant vote your own question']);
        }
    }
    public function checkUpdatedVoices(array $data)
    {
        $question = Question::where('id', $data['question_id'])->where('user_id', '!=', Auth::id())->first();
        
        if ($question) {
            // If not question owner
            $question_user = Question::where('id', $data['question_id'])->whereHas('voice', function ($query_voice) {
                $query_voice->where('user_id', Auth::id());
            })->first();

            if ($question_user) {
                // if have voice before
                if ($question_user->voice->first()->value == $data['value']) {
                    // If value is same
                    throw ValidationException::withMessages(['You cant vote on the same value again']);
                } else {
                    // If value is different
                    return 'updatedVoice';
                }
            } else {
                // if doesnt have voice before
                throw ValidationException::withMessages(['Please Create First']);
            }
        } else {
            // If question owner
            throw ValidationException::withMessages(['You cant vote your own question']);
        }
    }

    /**
     * Save a new resource
     *
     * @param [array] $data
     * @return void
     */
    public function create(array $data)
    {
        return Voice::create($data);
    }

    /**
     * find spesific resource
     *
     * @param [int] $id
     * @return void
     */
    public function find(int $id)
    {
        return Voice::find($id);
    }

    /**
     * Update spesific resource
     *
     * @param [array] $data
     * @param [int] $id
     * @return void
     */
    public function update(array $data, int $id)
    {
        return Voice::where('id', $id)->update($data) ? true : false;
    }

    /**
     * Get Voice with slug
     *
     * @param [string] $slug
     * @return void
     */
    public function getVoiceBySlug(string $slug)
    {
        return Voice::where('slug', $slug)->first();
    }
}