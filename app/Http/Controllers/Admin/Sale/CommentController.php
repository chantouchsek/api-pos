<?php

namespace App\Http\Controllers\Admin\Sale;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Sale $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Sale $sale)
    {
        $user = User::find(auth('api')->id());
        $comment = $sale->comment([
            'title' => 'Some title',
            'body' => 'Some body',
        ], $user);
        return $this->respond([
            'data' => $comment,
            'message' => 'Comment added.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
