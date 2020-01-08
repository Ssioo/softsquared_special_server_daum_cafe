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
         * API No. 1
         * API Name : 사용자 회원가입 API
         * 마지막 수정 날짜 : 20.01.08
         */
        case "signUp":
            $code = isSuccessSignUp($req->id, $req->password, $req->name);
            http_response_code(200);
            if ($code == 200) {
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "회원가입 성공";
            } else {
                $res->isSuccess = FALSE;
                $res->code = $code;
                $res->message = "회원가입 실패. API 문서를 참고해주세요.";
            }
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 2
         * API Name : 사용자 로그인 API
         * 마지막 수정 날짜 : 20.01.08
         * */
        case "signIn":
            http_response_code(200);

            if ()
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if ($jwt != null) {
                // jwt로 로그인 시
                $user = isValidHeader($jwt, JWT_SECRET_KEY);
                if (!$user) {
                    $res->isSuccess = FALSE;
                    $res->code = 403;
                    $res->message = "토큰 일치하지 않음";
                    addErrorLogs($errorLogs, $res, $req);
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "로그인 성공";
                $res->id = $user[0];
                $res->name = $user[1];
                echo json_encode($res, JSON_NUMERIC_CHECK);

            } else {
                // 최초 로그인
                $user = isValidUser($req->id, $req->password);
                if (!$user) {
                    // Body 양식 오류
                    $res->isSuccess = FALSE;
                    $res->code = 301;
                    $res->message = "유저 정보 없음";
                    addErrorLogs($errorLogs, $res, $req);
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
                $jwt = getJWToken($req->id, $req->password, JWT_SECRET_KEY);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "로그인 성공";
                $res->jwt = $jwt;
                $res->userInfo = (Object)Array();
                $res->userInfo->id = $user[0];
                $res->userInfo->name = $user[1];
                echo json_encode($res, JSON_NUMERIC_CHECK);
            }

            break;
        /*
         * API No. 3
         * API Name : 사용자 정보조회 API
         * 마지막 수정 날짜 : 20.01.08
         */
        case "info":
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            $user = isValidHeader($jwt, JWT_SECRET_KEY);

            if ($user) {
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "정보조회 실패";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }

            $result = getUserInfo($user[0]);

            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "정보조회 성공";
            $res->userInfo->id = $result["email"];
            $res->userInfo->name = $result["name"];
            echo json_encode($res, JSON_NUMERIC_CHECK);

            break;

        case "testDetail":
            http_response_code(200);
            $res->result = testDetail($vars["testNo"]);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 0
         * API Name : 테스트 Body & Insert API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "testPost":
            http_response_code(200);
            $res->result = testPost($req->name);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
