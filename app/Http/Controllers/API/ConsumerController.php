<?php

namespace App\Http\Controllers\API;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $consumers = Consumer::all();
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'errors' => 'Consumers not fetched',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response($consumers->jsonSerialize(), Response::HTTP_OK);
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

        try {
            $consumer->save();
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'request' => $request->all(),
                'errors' => 'Consumer not saved',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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
            $consumer = Consumer::find($id);
            if (empty($consumer)) {
                return response()->json([
                    'result' => false,
                    'error' => 'Consumer not found',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'error' => 'Consumer not found',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $consumer = Consumer::find($id);
            if (empty($consumer)) {
                return response()->json([
                    'result' => false,
                    'error' => 'Consumer not found',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'error' => 'Consumer not updated',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $consumer->fill($request->all());
        try {
            $consumer->save();
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'request' => $request->all(),
                'errors' => 'Consumer not updated',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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
            $consumer = Consumer::find($id);
            if (empty($consumer)) {
                return response()->json([
                    'result' => false,
                    'error' => 'Consumer not found',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'error' => 'Consumer not found',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $consumer->delete();
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'request' => $request->all(),
                'errors' => 'Consumer not deleted',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'result' => true,
            'message' => 'Consumer ' . $id . ' successfuly deleted',
        ], Response::HTTP_OK);
    }
}
