<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;
use Log;

class ApiResponse
{
    protected $debug = false;
    protected $dataWrap = false;
    private $status = "status";
    const MESSAGE = 'message';
    const SUCCESS = 'success';
    const COMMON_PROCESS_ERR = 'Something unexpected has happened. Please try again later';
    const COMMON_SOMTHNG_WRONG = 'common.something_went_wrong';
    const VALIDATION_GEN_ERR = 'Invalid or missing parameters in URL or request body';
    const COMMON_DATA_FOUND = 'Data found';
    const COMMON_NOT_FOUND = 'Record not found';
    const COMMON_NO_RECORDS = 'common.no_records_found';
    const COMMON_PERMISSION_ERR = 'Sorry! You do not have permission to perform this action.Please contact Administrator';
    const COMMON_TOKEN_ERR = 'No access token provided';
    const COMMON_DEPENDENCY_ERR = 'common.dependency_error';

    public function index()
    {
        return 'APIResponses repository';
    }

    public function __construct()
    {
        if (env('APP_DEBUG', true)) {
            $this->debug = true;
        }
        $this->dataWrap = true;
    }

    /**
     * API With created/ added item
     *
     * @param stdClass $created created object
     * @param array $headers http headers
     * @return json
     */
    public function withCreated($message, $headers = [])
    {
        return Response::json([
            $this->status => self::SUCCESS,

            // start with uppercase and end with a period
            self::MESSAGE => Str::ucfirst(Str::finish(trim($message), '.')),
        ], 201, array_merge($headers, $this->addHeaders()));
    }


    /**
     * Success response
     *
     * @param Array $headers
     * @return json
     */
    public function withSuccess($message = null, $headers = [])
    {
        return Response::json([
            $this->status => self::SUCCESS,
            // start with uppercase and end with a period
            self::MESSAGE => Str::ucfirst(Str::finish(trim($message), '.')),
        ], 200, array_merge($headers, $this->addHeaders()));
    }


    /**
     *  API With data
     *
     * @param stdClass $item single item object
     * @param array $headers http headers
     * @return json
     */
    public function withData($data, $message = null, $headers = [], $status = self::SUCCESS)
    {
        if ($message == null) {
            $message = __(self::COMMON_DATA_FOUND);
        }
        return Response::json([
            $this->status => $status,
            self::MESSAGE => Str::ucfirst(Str::finish(trim($message), '.')),
            'data' => $data
        ], 200, array_merge($headers, $this->addHeaders()));
    }

    /**
     *  API With array
     *
     * @param array $array response array
     * @param array $headers http headers
     * @return json
     */
    public function withArray($array, $headers = [])
    {
        return Response::json($this->dataWrap($array), 200, array_merge($headers, $this->addHeaders()));
    }

    /**
     *  API With Error
     *
     * @param string $message Error message
     * @param string $http_code http status code
     * @param array $headers http headers
     * @param \ErrorException $ex
     * @return json
     */
    public function withError($message, $httpCode, $errorCode, $headers = [], \Exception $ex = null)
    {
        $return = [
            'id' => ($errorCode ?  $errorCode : 'ROLE_GRID_ERROR_' . $httpCode),
            $this->status => 'fail',
            self::MESSAGE => Str::ucfirst(Str::finish(trim($message), '.')),
            'data' => [],
        ];

        if ($this->debug && $ex) {
            $return['detailed'] = [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'message' => $ex->getMessage()
            ];
        }
        return Response::json(
            $return,
            $httpCode,
            $headers
        );
    }

    /**
     * API with not found error
     *
     * @param string $message message for error [optional]
     * @param array $headers header array [optional]
     * @return withError
     */
    public function withNotFound($message = null, $errorCode = null, $headers = [])
    {
        if ($message == null) {
            $message = __(self::COMMON_NOT_FOUND);
        }
        return $this->withError($message, '404', $errorCode, array_merge($headers, $this->addHeaders()));
    }

    /**
     * with Not Enough Data
     *
     * @return withError
     */

    public function withInvalidData($message = null, $headers = [])
    {
        if ($message == null) {
            $message = __(self::VALIDATION_GEN_ERR);
        }
        return Response::json(
            [
                'id' => 'ROLE_GRID_ERROR_400',
                $this->status => 'fail',
                self::MESSAGE => Str::ucfirst(Str::finish(trim($message), '.')),
            ],
            400,
            $headers
        );
    }


    /**
     *  API With Validation Error
     *
     * @param string $message Error message
     * @param string $erros Errors
     * @param array $headers http headers
     * @param \ErrorException $ex
     * @return json
     */

    public function withValidationErrors($message = null, $errors = [], $headers = [])
    {
        if ($message == null) {
            $message = __(self::VALIDATION_GEN_ERR);
        }
        return Response::json(
            [
                'id' => 'ROLE_GRID_ERROR_422',
                $this->status => 'fail',
                // start with uppercase and end with a period
                self::MESSAGE => Str::ucfirst(Str::finish(trim($message), '.')),
                'errors'  => $errors
            ],
            422,
            $headers
        );
    }

    /**
     * with failed with http 400
     *
     * @return withError
     */
    public function withFail($message = null, $exception = null, $headers = [], $code = '400')
    {
        if ($message == null) {
            $message = (__(self::COMMON_PROCESS_ERR));
        }
        return $this->withError($message, $code, null, array_merge($headers, $this->addHeaders()), $exception);
    }

    /**
     * with Not Enough Permissions
     *
     * @return withError
     */
    public function withNotEnoughPermissions($headers = [])
    {
        /**
         * @var \Illuminate\Http\Request
         */
        $request = request();
        Log::info("403 Forbidden on ", [$request->getUri()]);
        return $this->withError(__(self::COMMON_PERMISSION_ERR), '403', null, array_merge($headers, $this->addHeaders()));
    }

    /**
     * with No Access token (unauthenticated)
     *
     * @return withError
     */
    public function withNoAccess($message = null, $exception = null, $headers = [])
    {
        if ($message == null) {
            $message = (__(self::COMMON_TOKEN_ERR));
        }
        return $this->withError($message, '401', null, array_merge($headers, $this->addHeaders()), $exception);
    }

    /**
     * Wrap the data
     *
     * @return mixed
     */
    public function dataWrap($data, $status = self::SUCCESS)
    {
        if ($this->dataWrap) {
            return [$this->status => $status, 'data' => $data];
        } else {
            return $data;
        }
    }

    /**
     * Add headers
     *
     * @return mixed
     */
    private function addHeaders()
    {
        return ['Request-Id' => uniqid()];
    }

    /**
     * Datatable Function
     *
     * @param Object $dataTable
     * @return Datatables
     */
    public function withDatatable($dataTable)
    {
        return Datatables::of($dataTable)->make(true);
    }
}
