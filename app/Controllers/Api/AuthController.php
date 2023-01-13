<?php

namespace App\Controllers\Api;

use App\Models\Users;
use App\Libraries\JWTCI4;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function login()
    {
        $rules = [
            'username'     => 'required',
            'password'     => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            $response = ['success' => false, "message" => $this->validator->getErrors()];
            return $this->response->setJSON($response)->setStatusCode(409);
        }

        $db = new Users;
        $user  = $db->where('username', $this->request->getVar('username'))->first("array");
        if ($user) {
            if (password_verify($this->request->getVar('password'), $user['password'])) {
                $jwt = new JWTCI4;
                $token = $jwt->token();
                $response = ['success' => true, 'message' => 'Data obtained', 'token' => $token, 'user' => $user];

                return $this->response->setJSON($response)->setStatusCode(200);
            } else {
                $response = ['success' => false, 'message' => 'The password you entered is incorrect'];
            }
        } else {

            $response = ['success' => false, 'message' => 'User not found'];
        }


        return $this->response->setJSON($response)->setStatusCode(409);
    }

    public function Register()
    {
        $rules = [
            'username' => ['rules' => 'required|min_length[4]|max_length[255]|is_unique[users.username]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],
            'confirm_password'  => ['label' => 'confirm password', 'rules' => 'matches[password]']
        ];


        if ($this->validate($rules)) {
            $model = new Users();
            $data = [
                'username'    => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $model->save($data);

            $response = ['success' => true, 'message' => 'Registered Successfully'];
            return $this->response->setJSON($response)->setStatusCode(200);
        } else {
            $response = [
                'success' => false,
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->response->setJSON($response)->setStatusCode(409);
        }
    }
}
