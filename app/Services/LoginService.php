<?php

namespace App\Services;

use App\Exceptions\LoginFailException;
use App\Models\User as User;
use App\Repositories\LoginRepository as LoginRepo;
use App\Services\CommonService as CommonService;
use DateTime;

class LoginService
{

    public $userModel;
    public $commonService;
    protected $loginRepo;
    public function __construct(User $user, CommonService $commonService, LoginRepo $loginRepo)
    {
        $this->userModel = $user;
        $this->commonService = $commonService;
        $this->loginRepo = $loginRepo;
    }

    public function login($request)
    {
        $requiredFields = [
            'email' => "bail|required|email",
            'password' => 'required',
        ];

        $request->validate($requiredFields);
        $params = $request->all();

        $userData = $this->loginRepo->login($params['email'], $this->commonService->hashPassword($params['password']));

        if (!$userData) {
            throw new \App\Exceptions\LoginFailException();
        }

        $accessTokens = $this->handleAccessTokens($userData['id']);
        return [
            'user_data' => $userData,
            'access_token' => $accessTokens['access_token'],
            'refresh_token' => $accessTokens['refresh_token'],
            'token_expires_at' => $accessTokens['expires_at'],
        ];
    }

    public function handleAccessTokens($userId)
    {
        $tokens = $this->generateAccessTokens($userId);
        $this->loginRepo->updateAccessTokens($userId, $tokens);
        return $tokens;
    }

    public function generateAccessTokens()
    {
        $accessToken = bin2hex(random_bytes(32));
        $refreshToken = bin2hex(random_bytes(32));
        $today = new \DateTime('now');
        $expiresAt = $today->modify('+1 day');
        return ['access_token' => $accessToken, 'refresh_token' => $refreshToken, 'expires_at' => $expiresAt];
    }

    public static function findUserByAccessToken($accessToken)
    {
        return User::findUserByAccessToken($accessToken);
    }

    public function logout($userId)
    {
        return $this->userModel->logout($userId);
    }
}
