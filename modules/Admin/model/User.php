<?php

namespace Admin\Model;

class User extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\User);
    }

    /**
     * User system auth
     * @param string $email
     * @param string $pass
     * @return array|null
     */
    public function auth($email, $password) {
        $dml = $this->getEntity()
                ->select()
                ->from()
                ->where(['email' => [$email, \Kf\Database\Criteria::CONDITION_EQUAL], 'password' => $password]);
        return $this->fetchBySql($dml->query, $dml->input);
    }

}
