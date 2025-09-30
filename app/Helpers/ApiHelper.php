<?php

namespace App\Helpers;

use App\Models\ApiManagement;
use App\Http\Controllers\Jwt;
use App\Models\FundRequest;
use App\Models\Transaction;
use App\Models\ApiLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;



class ApiHelper
{
    /**
     * Generate a unique request ID.
     *
     * @return string
     */
    public static function generateRequestId()
    {
        return time() . rand(1000, 9999);
    }

    /**
     * Prepare API headers with a JWT token and optional additional headers.
     *
     * @param string $jwtToken
     * @param array $additionalHeaders
     * @param string $partnerId
     * @return array
     */
    public static function getApiHeaders($jwtToken, $additionalHeaders = [], $partnerId = 'PS005962')
    {
        return array_merge([
            'Token' => $jwtToken,
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => $partnerId
        ], $additionalHeaders);
    }

    /**
     * Generate a JWT token.
     *
     * @param string $requestId
     * @param string $partnerId
     * @param string $secretKey
     * @return string
     */
    public static function generateJwtToken($requestId, $partnerId, $secretKey)
    {
        $timestamp = time();
        $payload = [
            'timestamp' => $timestamp,
            'partnerId' => $partnerId,
            'reqid' => $requestId
        ];

        return Jwt::encode(
            $payload,
            $secretKey,
            'HS256'
        );
    }

    /**
     * Generate a unique reference ID.
     *
     * @param string $prefix
     * @return string
     */
    public static function generateReferenceId($prefix = 'RECH')
    {
        return $prefix . time() . rand(1000, 9999);
    }

    /**
     * Fetch API URL from the database based on API name.
     *
     * @param string $apiName
     * @return string|null
     * @throws \Exception
     */
    public static function getApiUrl($apiName)
    {
        $apiDetails = ApiManagement::where('api_name', $apiName)->first();

        if (!$apiDetails) {
            throw new \Exception("API not found for name: {$apiName}");
        }

        return $apiDetails->api_url;
    }

     /**
     * Log API requests and responses
     *
     * @param string $apiName The name of the API
     * @param array $requestPayload The request data sent to the API
     * @param array|null $responseData The response received from the API
     * @param string|null $requestId Unique request identifier
     * @param string|null $referenceId Reference ID for the transaction
     * @param string $status Status of the API call (pending, success, failed)
     * @param string|null $errorMessage Error message if the call failed
     * @param float|null $executionTime Time taken to execute the API call in seconds
     * @return \App\Models\ApiLog
     */
    public static function logApiCall(
        string $apiName, 
        array $requestPayload, 
        ?array $responseData = null, 
        ?string $requestId = null,
        ?string $referenceId = null,
        string $status = 'pending', 
        ?string $errorMessage = null,
        ?float $executionTime = null
    ) {
        try {
            $userId = Auth::id();
            $ipAddress = Request::ip();
            
            return ApiLog::create([
                'user_id' => $userId,
                'api_name' => $apiName,
                'request_id' => $requestId,
                'reference_id' => $referenceId,
                'request_payload' => $requestPayload,
                'response_data' => $responseData,
                'status' => $status,
                'error_message' => $errorMessage,
                'ip_address' => $ipAddress,
                'execution_time' => $executionTime
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log API call: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update an existing API log with response data
     *
     * @param \App\Models\ApiLog|int $apiLog ApiLog model or ID
     * @param array $responseData The response received from the API
     * @param string $status Status of the API call (success, failed)
     * @param string|null $errorMessage Error message if the call failed
     * @param float|null $executionTime Time taken to execute the API call in seconds
     * @return bool
     */
    public static function updateApiLog(
        $apiLog,
        ?array $responseData = null,
        string $status = 'success',
        ?string $errorMessage = null,
        ?float $executionTime = null
    ) {
        try {
            if (is_numeric($apiLog)) {
                $apiLog = ApiLog::find($apiLog);
            }

            if (!$apiLog) {
                return false;
            }

            $updateData = [
                'status' => $status
            ];

            if ($responseData !== null) {
                $updateData['response_data'] = $responseData;
            }

            if ($errorMessage !== null) {
                $updateData['error_message'] = $errorMessage;
            }

            if ($executionTime !== null) {
                $updateData['execution_time'] = $executionTime;
            }

            return $apiLog->update($updateData);
        } catch (\Exception $e) {
            Log::error('Failed to update API log: ' . $e->getMessage());
            return false;
        }
    }

    public static function calculations($userId, $amount, $referenceId)
    {
        try {
            return DB::transaction(function () use ($userId, $amount, $referenceId) {
                // Find the latest approved fund request for the user
                $latestFundRequest = FundRequest::where('user_id', $userId)
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if (!$latestFundRequest) {
                    throw new \Exception('No approved fund request found');
                }

                // Calculate new balance
                $currentBalance = $latestFundRequest->amount;
                $newBalance = max(0, $currentBalance - $amount);

                // Update the fund request
                $latestFundRequest->update([
                    'amount' => $newBalance
                ]);

                // Create a transaction record
                Transaction::create([
                    'user_id' => $userId,
                    'amount' => $amount,
                    'type' => 'debit',
                    'status' => 'completed',
                    'description' => 'Recharge transaction - ' . $referenceId
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Wallet calculation failed: ' . $e->getMessage());
            return false;
        }
    }
   
} 