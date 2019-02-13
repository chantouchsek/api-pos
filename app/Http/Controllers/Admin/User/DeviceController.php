<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $uniqueDevice = UserDevice::where('user_id', '=', $request->user('api')->id)
            ->where('player_id', '=', $request->get('player_id'))->first();

        if (empty($uniqueDevice)) {

            $device = new UserDevice($request->except(['user_id']));

            $device->user_id = $request->user('api')->id;

            $device->save();

            DB::commit();

            return $this->respondCreated('Device has been registered successful.');
        }

        $uniqueDevice->fill($request->only('subscribed'));

        $uniqueDevice->save();

        DB::commit();

        return $this->respondCreated('Your device updated.');
    }

    /**
     * @param int $playerId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update($playerId, Request $request)
    {
        DB::beginTransaction();

        $userDevice = UserDevice::where('player_id', $playerId)->first();

        $userDevice->update($request->only('subscribed'));

        DB::commit();

        return $this->respondCreated('Item updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $playerId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($playerId)
    {
        $device = UserDevice::where('player_id', $playerId)->first();
        DB::beginTransaction();
        if (!empty($device)) {
            $device->delete();
        }
        DB::commit();
        return $this->respondCreated('Item delete.');
    }
}
