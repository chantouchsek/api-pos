<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class NotificationController extends Controller
{
    /**
     * @var NotificationTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * The constructor for the controller.
     *
     * @param NotificationTransformer $transformer The transformer used to transform the model.
     */
    public function __construct(NotificationTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        if (Input::get('limit')) {
            $this->setPagination(Input::get('limit'));
        }
        $user = User::find(request()->user('api')->id);
        $pagination = $user->notifications()->paginate($this->getPagination());

        $unreadNotifications = $this->transformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, [
            'data' => $unreadNotifications
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unReads(): JsonResponse
    {
        if (Input::get('limit')) {
            $this->setPagination(Input::get('limit', 500));
        }
        $user = User::find(request()->user('api')->id);
        $pagination = $user->unreadNotifications()->paginate($this->getPagination());

        $unreadNotifications = $this->transformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, [
            'data' => $unreadNotifications
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function readNotification(): JsonResponse
    {
        $user = User::find(request()->user('api')->id);
        foreach ($user->notifications as $notification) {
            $notification->markAsRead();
        }
        return $this->respondCreated('The Notification has been read.');
    }
}
