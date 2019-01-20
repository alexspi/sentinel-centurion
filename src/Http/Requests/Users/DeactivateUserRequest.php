<?php

namespace Deltoss\Centurion\Http\Requests\Users;

use Sentinel;
use Activation;
use Deltoss\Centurion\Http\Requests\CenturionFormRequest;

class DeactivateUserRequest extends CenturionFormRequest
{
    public $user;

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($validator->errors()) > 0)
                return;
            
            // Get id from route
            $id = null;
            $possibleParameterNames = ['id', 'userId', 'user', 'Id', 'User', 'UserId', 'ID', 'userID', 'UserID'];
            foreach($possibleParameterNames as $possibleParameterName)
            {
                $id = $this->route($possibleParameterName);
                if ($id)
                    break;
            }
            
            $this->user = Sentinel::findById($id);
            if (!($this->user))
                abort(404);
            $activated = Activation::completed($this->user);
            $activationExists = Activation::exists($this->user);
            if (!$activated && !$activationExists)
                $validator->errors()->add('Already Deactivated', trans('centurion::validation.account.deactivated'));
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}