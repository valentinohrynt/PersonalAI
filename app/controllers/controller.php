<?php

class Controller {
    public function __construct() {
        require_once 'app/core/core.php';
        require_once 'app/models/visitorlogs.php';
        $this->core = new Core();
        $this->visitorLogs = new VisitorLogs();
    }

    public function index() {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d');
        $existingVisitorLogs = $this->visitorLogs->checkExistingVisitorLogs($ip_address, $date);
        if ($existingVisitorLogs === true) {
            require_once 'app/views/index.php';
            exit();
        } elseif ($existingVisitorLogs === false) {
            $visitorLogs = $this->visitorLogs->addVisitorLog($ip_address);
            require_once 'app/views/index.php';
            exit();
        }
    }

    public function sendPrompt() {
        $data = [
            'lang' => $_POST[ 'lang' ],
            'prompt' => $_POST[ 'user_prompt' ]
        ];
        $answer = $this->core->getAnswer( $data );
        if ( $answer == null ) {
            $answer = 'I am sorry, I do not understand.';
            echo json_encode(['response' => $answer]);
        } else {
            echo json_encode(['response' => $answer]);
        }
    }

    public function clearSession() {
        session_destroy();
        return true;
    }
}