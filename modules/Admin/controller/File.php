<?php

namespace Admin\Controller;

class File extends \Kf\Module\Controller {

    public function css() {
        header("Content-type: text/css");
        if (!$this->request->get->file) {
            die('');
        }
        $filename = \Kf\System\Crypt::decode(base64_decode($this->request->get->file));
        if (!file_exists($filename)) {
            die('');
        }
        echo file_get_contents($filename);
        die;
    }

    public function js() {
        header("Content-type: text/javascript");
        if (!$this->request->get->file) {
            die('');
        }
        $filename = \Kf\System\Crypt::decode(base64_decode($this->request->get->file));
        if (!file_exists($filename)) {
            die('');
        }
        echo file_get_contents($filename);
        die;
    }

}
