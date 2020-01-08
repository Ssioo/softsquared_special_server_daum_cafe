<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        /*
         * API No. 40
         * API Name : Cafe 리스트 API
         * 마지막 수정 날짜 : 20.01.09
         */
        case "list":
            http_response_code(200);
            // DUMMY
            $cafe1 = array("name" => "special Server");
            $cafe2 = array("name" => "special Server2");
            $cafe3 = array("name" => "special Server3");
            $cafe4 = array("name" => "dummy");
            $res->result = array($cafe1, $cafe2, $cafe3, $cafe4);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
