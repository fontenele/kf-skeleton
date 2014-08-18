<?php

namespace Admin\Controller;

Class Auth extends \Kf\Module\Controller {

    public function init() {
        
    }

    public function login() {
        try {
            if ($this->request->isPost() && $this->request->post->offsetGet('email') && $this->request->post->offsetGet('password')) {
                $service = new \Admin\Service\User;
                $user = $service->auth($this->request->post->offsetGet('email'), $this->request->post->offsetGet('password'));
                if ($user) {
                    $session = new \Kf\System\Session('system');
                    $user['photo'] = null;

                    if (\Kf\Kernel::$config['system']['auth']['gravatar']) {
                        $gravatarUrl = 'http://www.gravatar.com/avatar/%s?s=300';
                        $gravatar = sprintf($gravatarUrl, md5($this->request->post->offsetGet('email')));
                        $user['photo'] = $gravatar;
                    }
                    $session->identity = $user;
                    \Kf\System\Messenger::success('Bem vindo ' . $this->request->post->offsetGet('email'));
                    $this->redirect(\Kf\Kernel::$router->default);
                } else {
                    \Kf\System\Messenger::error('Login inválido');
                    $this->redirect('admin/auth/login');
                }
            }
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function logout() {
        try {
            session_destroy();
            $this->redirect('admin/auth/login');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
