<?php

namespace App\Services;

use App\Models\User as User;
use App\Services\CommonService as CommonService;

class LoginService
{

    public $userModel;
    public $commonService;
    public function __construct(User $user, CommonService $commonService)
    {
        $this->userModel = $user;
        $this->commonService = $commonService;
    }

    public function getLoginErrorMessage($validatorObj)
    {
        $msg = '';
        foreach ($validatorObj->getMessages() as  $messages) {
            foreach ($messages as $message) {
                $msg .= $message . ' ';
            }
        }
        return $msg;
    }

    public function login($email, $password)
    {
        $query = $this->userModel->login($email, $this->commonService->hashPassword($password));
        if (!$query) {
            return false;
        }
        return [
            'id'=>$query->id,
            'name'=>$query->name,
            'email'=>$query->email,
            'mobile_no'=>$query->mobile_no,
        ];
    }

    public function handleAccessTokens($userId)
    {
        $tokens = $this->generateAccessTokens($userId);
        $this->userModel->updateAccessTokens($userId,$tokens['access_token'], $tokens['refresh_token']);
        return $tokens;
    }

    public function generateAccessTokens()
    {
        $accessToken = bin2hex(random_bytes(32));
        $refreshToken = bin2hex(random_bytes(32));
        return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
    }

    public static function findUserByAccessToken($accessToken){
        return User::findUserByAccessToken($accessToken);
    }

    public function logout($userId){
        return $this->userModel->logout($userId);
    }
}
