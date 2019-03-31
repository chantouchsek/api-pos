<?php

namespace App\Http\Controllers\Admin\Sale;

use App\Jobs\Sale\Comment\Created as CommentCreated;
use App\Models\Sale;
use App\Models\User;
use App\Traits\Authorizable;
use App\Transformers\CommentTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    use Authorizable;
    /**
     * @var CommentTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * CommentsController constructor.
     * @param CommentTransformer $transformer The transformer used to transform the model
     */
    public function __construct(CommentTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Sale $sale)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Sale $sale
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function store(Request $request, Sale $sale)
    {
        DB::beginTransaction();

        $user = User::find(auth()->id());

        if (!$request->hasFile('files')) {
            $this->validate($request, [
                'body' => 'required|min:2|string'
            ]);
        }

        $comment = $sale->comment([
            'title' => "Sale number: # " . $sale->sale_number,
            'body' => $request->input('body')
        ], $user);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $comment->addMedia($file)->toMediaCollection('comment');
            }
        }

        $comment->load(['creator:id,name,email', 'media']);

        $commentCreated = (new CommentCreated($sale, $comment))->delay(Carbon::now()->addSeconds(3));

        dispatch($commentCreated);

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($sale),
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
