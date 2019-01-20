<?php


namespace App\Traits;


use App\Http\Requests\Admin\Media\DeleteRequest;
use App\Http\Requests\Admin\Media\StoreRequest;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;

trait MediaTrait
{

    /**
     * @var
     */
    protected $transformer;

    /**
     * MediaTrait constructor.
     * @param $transformer
     */
    public function __construct($transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param StoreRequest $request
     * @param Model $model
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    protected function upload(StoreRequest $request, Model $model, string $type = 'default')
    {
        $input = $request->all();
        if ($request->hasFile('files')) {
            $model->addMultipleMediaFromRequest(['files'])->each(function ($fileAdder) use ($type) {
                $fileAdder->toMediaCollection($type);
            });
        }
        $model->update($input);
        return $this->respond(['data' => $this->transformer->transform($model), 'message' => 'Files uploaded.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Model $model
     * @param DeleteRequest $request
     * @param Media $upload
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    protected function delete(Model $model, DeleteRequest $request, Media $upload, string $type = 'default')
    {
        if ($model->hasMedia($type)) {
            $model->deleteMedia($upload);
        }
        return $this->respond([
            'data' => $this->transformer->transform($model),
            'message' => 'Item deleted.'
        ]);
    }
}
