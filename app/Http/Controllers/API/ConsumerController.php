<?php

namespace App\Http\Controllers\API;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Consumer::all()->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Consumer::getValidator($request->all());

        if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'request' => $request->all(),
                    'errors' => $validator->messages(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $consumer = new Consumer();
        $consumer->fill($request->all());
        $consumer->save();

        return response($consumer->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consumer  $consumer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $consumer = Consumer::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'result' => false,
                'error' => 'Consumer not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response($consumer->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Mixed $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Consumer::getValidator($request->all());

        if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'request' => $request->all(),
                    'errors' => $validator->messages(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $consumer = Consumer::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'result' => false,
                'error' => 'Consumer not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $consumer->fill($request->all());
        $consumer->save();

        return response($consumer->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consumer  $consumer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $consumer = Consumer::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'result' => false,
                'error' => 'Consumer not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $consumer->delete();

        return response()->json([
            'result' => true,
            'message' => 'Consumer ' . $id . ' successfuly deleted',
        ], Response::HTTP_OK);
    }
}
