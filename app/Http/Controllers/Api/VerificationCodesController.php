<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Str;

class VerificationCodesController extends Controller
{
    //
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        if (!app()->environment('production')){
            $code = '1234';
        } else {
            $code = str_pad(random_int(1, 9999), 4,0,STR_PAD_LEFT);

            try{
                $result = $easySms->send($phone,[
                    'template'  => config('easysms.gateways.aliyun.templates.register'),
                    'data'      => [
                        'code' => $code,
                        'product'=>'酷龙学院'
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        $key = Str::random(15);
        $cacheKey = 'verificationCode_'.$key;
        $expiredAt = now()->addMinutes(5);
        //缓存验证码5min
        \Cache::put($cacheKey, ['phone' => $phone, 'code' => $code], $expiredAt);

        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);

    }
}
