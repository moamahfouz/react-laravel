<?php


namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Upload file
     *
     * @param $file
     * @param $after_path
     * @param $slug
     * @return string
     */
    public function uploadFile($file, $after_path, $slug): string
    {
        $filename = $slug . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = public_path() . '/uploads/' . $after_path . '/';
        $file->move($path, $filename);
        return '/uploads/' . $after_path . '/' . $filename;
    }

}
