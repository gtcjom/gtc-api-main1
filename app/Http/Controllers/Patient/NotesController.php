<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotesController extends Controller
{

    public function index(NoteService $notes, $id)
    {
        return NoteResource::collection($notes->getNotes($id)->load('addedBy', 'patient'));
    }

	public function show(Note $notes)
	{
		return response()->json([
			'data' => new NoteResource($notes->load('addedBy', 'patient')),
			'message' => 'Note created successfully.'
		], Response::HTTP_OK);
	}

    public function store(Request $request)
    {
		// validate fields
        $request->validate([
			'notes' => ['required','string','max:250'],
            'remarks' => ['required','string','max:500'],
			'patient_id' => ['required','string'],
			'datetime' => ['required'],
			'added_by' => ['nullable'],
		]);

		// create a new note
		$note = Note::create(array_merge([
			'notes' => $request->notes,
            'remarks' => $request->remarks,
			'added_by' => $request->user()->user_id,
			'patient_id' => $request->patient_id,
			'datetime' => $request->datetime,
		]));

		return response()->json([
			'data' => new NoteResource($note),
			'message' => 'Note created successfully.'
		], Response::HTTP_CREATED);

    }

    public function update(Note $notes, Request $request)
    {
        // validate fields
		$request->validate([
			'notes' => ['required','string','max:250'],
            'remarks' => ['required','string','max:500'],
			'datetime' => ['required'],
		]);

        // update the notes
		$notes->update([
			'notes' => $request->notes,
			'remarks' => $request->remarks,
			'datetime' => $request->datetime,
		]);

		return response()->json([
			'data' => new NoteResource($notes),
			'message' => 'Note updated successfully.'
		], Response::HTTP_OK);
    }

    public function destroy(Note $notes)
    {
		// delete the item
        $notes->delete();
		return response()->json(['message' => 'Note deleted successfully']);
    }
}
