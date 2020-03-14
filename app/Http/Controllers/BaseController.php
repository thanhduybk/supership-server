<?php


namespace App\Http\Controllers;


class BaseController extends Controller
{
    public function sendResponse(string $message, $result, int $status)
    {
        $response = [
            'success' => true,
            'result' => $result,
            'message' => $message
        ];

        return response()->json($response, (int)$status);
    }

    public function sendError(string $errorMsg, $errors, int $status) {
        $response = [
            'success' => false,
            'message' => $errorMsg
        ];

        if (!empty($errors)) {
            $response['result'] = $errors;
        }

        return response()->json($response, $status);
    }
}
