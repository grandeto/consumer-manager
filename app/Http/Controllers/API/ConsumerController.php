<?php

namespace App\Http\Controllers\API;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ConsumerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

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
                'errors' => 'Consumers not fetched. Please try again later.',
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
        $data = $request->all();
        $data = Self::normalizeCity($data, "city");

        $validator = Consumer::getValidator($data);
        if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'request' => $request->all(),
                    'errors' => $validator->messages(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $consumer = new Consumer();
        $consumer->fill($data);

        try {
            $consumer->save();
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'request' => $data,
                'errors' => 'Consumer not saved. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response($consumer->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Mixed $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $consumer = Consumer::find($id);
            if (empty($consumer)) {
                return response()->json([
                    'result' => false,
                    'errors' => 'Consumer not found',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'errors' => 'Consumer not found. Please try again later.',
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
        $data = $request->all();
        $data = Self::normalizeCity($data, "city");

        $validator = Consumer::getValidator($data, $request->method());
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
                    'errors' => 'Consumer not found',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'errors' => 'Consumer not updated. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $consumer->fill($data);

        try {
            $consumer->save();
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'request' => $data,
                'errors' => 'Consumer not updated. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response($consumer->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Mixed $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $consumer = Consumer::find($id);
            if (empty($consumer)) {
                return response()->json([
                    'result' => false,
                    'errors' => 'Consumer not found',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'errors' => 'Consumer not deleted. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $consumer->delete();
        } catch (\Exception $err) {
            return response()->json([
                'result' => false,
                'request' => $request->all(),
                'errors' => 'Consumer not deleted. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'result' => true,
            'message' => 'Consumer ' . $id . ' successfuly deleted',
        ], Response::HTTP_OK);
    }

    /**
     * Normalize name property
     *
     * @param  Array  $arr
     * @param  String $key
     * @return Array
     */
    private function normalizeCity($arr, $key)
    {
        if (!empty($arr[$key]) && is_string($arr[$key])) {
            $arr[$key] = strtolower($arr[$key]);
            $arr[$key]= ucfirst($arr[$key]);
        }

        return $arr;
    }
}
