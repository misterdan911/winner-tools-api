<?php

namespace App\BusinessLogic;

use App\BusinessLogic\Repository\SubscriptionRepository;
use App\Models\Subscription;
use App\Models\WhitelistFreeTrial;
use Illuminate\Support\Facades\Validator;

/**
 * Hanya me-return 1 error message saja
 */
class InputValidationHelperSimplified
{
    public function validate($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $data = [];
            $errors = $validator->errors();

            $message = "";

            foreach ($rules as $key => $val)
            {
                if ($errors->has($key)) {
                    $message = $errors->first($key);
                    break;
                }
            }

            $response = [
                'status' => 'fail',
                'message' => $message
            ];
        }
        else {
            $response = ['status' => 'success'];
        }

        return $response;
    }
}
