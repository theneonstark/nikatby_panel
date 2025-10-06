<?php

namespace App\Http\Controllers\Service\Paysprint;

use App\Models\ApiManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RechargeController
{
    private $partnerId = 'PS005962';
    private $secretKey = 'UFMwMDU5NjJjYzE5Y2JlYWY1OGRiZjE2ZGI3NThhN2FjNDFiNTI3YTE3NDA2NDkxMzM=';

    // Method to generate JWT token
    private function generateJwtToken($requestId)
    {
        $timestamp = time();
        $payload = [
            'timestamp' => $timestamp,
            'partnerId' => $this->partnerId,
            'reqid' => $requestId
        ];

        return JwtController::encode(
            $payload,
            $this->secretKey,
            'HS256'
        );
    }

    public function index()
    {
        return Inertia::render('Paysprint/Recharge');
    }

    private function callDynamicApi($apiName, $payload = [], $additionalHeaders = [])
    {
        try {
            //to get api response from db 
            $apiDetails = ApiManagement::where('api_name', $apiName)->first();

            if (!$apiDetails) {
                throw new \Exception("API not found for name: {$apiName}");
            }

            // Generate unique request ID and JWT token
            $requestId = time() . rand(1000, 9999);
            $jwtToken = $this->generateJwtToken($requestId);

            // Prepare headers
            $headers = array_merge([
                'Token' => $jwtToken,
                'accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => $this->partnerId
            ], $additionalHeaders);

            // Step 4: Prepare payload
            $payloadJson = json_encode($payload);

            // Make the API call
            $response = \MyHelper::curl($apiDetails->api_url, "POST", $payloadJson, $headers, 'no');

            dd($response['response']);

            // Log the API call
            // Log::info('Dynamic API Call', [
            //     'api_name' => $apiName,
            //     'url' => $apiDetails->api_url,
            //     'payload' => $payload,
            //     'response' => $response->json()
            // ]);

            // return $response['response']->json();
        } catch (\Exception $e) {
            Log::error('Dynamic API Call Failed', [
                'api_name' => $apiName,
                'error' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'message' => 'API call failed: ' . $e->getMessage()
            ];
        }
    }

    public function getOperators()
    {
        try {
            // Call the GetOperator API dynamically
            $responseData = $this->callDynamicApi('GetOperator');

            // Save to database if the API call is successful
            // if (isset($responseData['status']) && $responseData['status'] === true) {
            //     $this->saveOperatorsToDatabase($responseData['data']);
            // }

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Failed to fetch operators: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch operators: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dorecharge(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'operator' => 'required|numeric',
                'canumber' => 'required|string',
                'amount' => 'required|numeric|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            $user = $request->user();
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }
            $amount = $request->input('amount');
            // Fetch commission rate
            // $commissionData = RechargeCommission::where('operator_id', $request->operator)->first();
            // $commissionRate = $commissionData ? $commissionData->our_commission : 0;
            // $commissionAmount = ($amount * $commissionRate) / 100;
            // $totalDeduction = $amount - $commissionAmount;
            // // Calculate available balance
            // $totalApproved = FundRequest::getAvailableBalance($user->id);
            // $spentAmount = Transaction::where('user_id', $user->id)
            //     ->where('status', 'completed')
            //     ->where('type', 'debit')
            //     ->sum('amount');
            // $lockedAmount = Transaction::where('user_id', $user->id)
            //     ->where('status', 'pending')
            //     ->where('type', 'debit')
            //     ->sum('amount');
            // $remainingBalance = $totalApproved - ($spentAmount + $lockedAmount);
            // // Check sufficient funds
            // if ($totalDeduction > $remainingBalance) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Insufficient funds for recharge and commission'
            //     ], 403);
            // }
            // Generate reference ID
            $referenceId = 'RECH' . time() . rand(1000, 9999);
            // Create pending transaction
            // $pendingTransaction = Transaction::create([
            //     'user_id' => $user->id,
            //     'amount' => $totalDeduction,
            //     'type' => 'debit',
            //     'status' => 'pending'
            // ]);

            // Prepare API payload
            $payload = [
                'operator' => (int)$request->operator,
                'canumber' => $request->canumber,
                'amount' => (int)$request->amount,
                'referenceid' => $referenceId
            ];
            $apiName = 'DoRecharge'; //api logs 
            $startTime = microtime(true);
            $apiLog = \App\Helpers\ApiHelper::logApiCall(
                $apiName,
                $payload,
                null,
                null,
                $referenceId,
                'pending'
            );
            $responseData = $this->callDynamicApi($apiName, $payload);
            $executionTime = microtime(true) - $startTime;        // Calculate execution time
            // Check API response - keeping this condition exactly as it was
            if (!isset($responseData['status']) || $responseData['status'] === false || ($responseData['response_code'] ?? '') === '0') {
                // Update API log with failure status
                \App\Helpers\ApiHelper::updateApiLog(
                    $apiLog,
                    $responseData,
                    'failed',
                    $responseData['message'] ?? 'API call failed',
                    $executionTime
                );
                // $pendingTransaction->update(['status' => 'failed']);
                // Log::error('API call failed: ' . json_encode($responseData));
                // return response()->json(['status' => false, 'message' => 'API call failed', 'error_details' => $responseData], 500);
            }

            // Update API log with success status
            // \App\Helpers\ApiHelper::updateApiLog(
            //     $apiLog,
            //     $responseData,
            //     'success',
            //     null,
            //     $executionTime
            // );

            // Store recharge transaction
            // $transaction = RechargeTransaction::create([
            //     'operator' => $request->operator,
            //     'canumber' => $request->canumber,
            //     'amount' => $amount,
            //     'our_commission' => $commissionRate,
            //     'commission_amount' => $commissionAmount,
            //     'referenceid' => $referenceId,
            //     'status' => $responseData['status'] ? 'success' : 'failed',
            //     'message' => $responseData['message'] ?? 'Transaction processed',
            //     'response_code' => $responseData['response_code'] ?? '',
            //     'operatorid' => $responseData['operatorid'] ?? '',
            //     'ackno' => $responseData['ackno'] ?? '',
            //     'created_at' => Carbon::now('Asia/Kolkata'), // for time 
            //     'updated_at' => Carbon::now('Asia/Kolkata'),
            // ]);

            // Update transaction status and balance
            // if (isset($responseData['status']) && $responseData['status'] === true && ($responseData['response_code'] ?? '') !== '0') {
            //     $pendingTransaction->update(['status' => 'completed']);

            //     // Update debit balance
            //     $newSpentAmount = Transaction::where('user_id', $user->id)
            //         ->where('status', 'completed')
            //         ->where('type', 'debit')
            //         ->sum('amount');
            //     $newRemainingBalance = max(0, $totalApproved - $newSpentAmount);

            //     DebitBalance::updateOrCreate(
            //         ['user_id' => $user->id],
            //         ['balance' => $newRemainingBalance]
            //     );
            // } else {
            //     $pendingTransaction->update(['status' => 'failed']);
            // }
            // Log::info('Transaction created successfully:', $transaction->toArray());
            // Add invoice data
            // $responseData['invoice'] = $this->generateInvoiceData($transaction);
            return response()->json($responseData);
        } catch (\Exception $e) {
            // Log the exception in the API log if it exists
            if (isset($apiLog) && isset($startTime)) {
                $exceptionExecutionTime = microtime(true) - $startTime;
                \App\Helpers\ApiHelper::updateApiLog(
                    $apiLog,
                    isset($responseData) ? $responseData : null,
                    'failed',
                    $e->getMessage(),
                    $exceptionExecutionTime
                );
            }

            Log::error('Recharge processing failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to process recharge: ' . $e->getMessage()
            ], 500);
        }
    }
}
