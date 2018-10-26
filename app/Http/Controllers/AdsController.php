<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{

    public function show($id = null)
    {
        if (isset($id)) {
            return Ad::find($id) ?:[];
        }
        return Ad::query()->adsList()->get();
    }

    public function update(Request $request, $id)
    {
        $validateInput = $this->validateAd($request->json()->all());
        if ($validateInput->fails()) {
            return response(['errors' => $validateInput->errors()], 422);
        }

        $postData = $request->json()->all();
        $validateInput = $this->validateAd($postData);

        if ($validateInput->fails()) {
            return response($validateInput->errors(), 422);
        }

        $userId = Auth::user()->id;
        $currentAd = Ad::query()->where(['user_id' => $userId,'id' => $id])->first();
        if (!$currentAd) {
            return response(['error'=>'Matching ad not found'], 401);
        }

        self::populateModel($currentAd, $postData);
        return response(['success' => 'ok',"adId" => $currentAd->id], 200);
    }

    public function create(Request $request)
    {
        $postData = $request->json()->all();
        $validateInput = $this->validateAd($postData);

        if ($validateInput->fails()) {
            return response($validateInput->errors(), 422);
        }

        $request->json();
        $newAd = new Ad();

        self::populateModel($newAd, $postData);
        $this->informSocialMedia($newAd);
        return response(['success' => 'ok',"adId" => $newAd->id], 200);
    }

    /**
     * @param $data
     * @return \Illuminate\Validation\Validator
     */

    public function validateAd($data) : \Illuminate\Validation\Validator
    {
        return Validator::make(
            $data,
            [
            'title' => 'required|max:100',
            'description' => 'required|max:65535',
            'price' => 'numeric|required|between:0,9999999.99'
            ]
        );
    }

    private static function populateModel($ad, $data)
    {
        $ad->title = $data['title'];
        $ad->description = $data['description'];
        $ad->price = $data['price'];
        $ad->user_id = Auth::user()->id;
        $ad->save();
    }

    public function informSocialMedia(Ad $ad)
    {
        // async messaging to social media using Queue, either local or i.e. SQS.
        // Queue::push('NotifiySocMedia',$ad);
    }
}
