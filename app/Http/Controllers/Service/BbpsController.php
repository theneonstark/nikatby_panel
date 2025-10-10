<?php

namespace App\Http\Controllers\Service;

use App\Models\BbpsFetchBill;
use App\Models\BbpsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BbpsController
{
    public function services()
    {

        $services = BbpsServices::all()
            ->groupBy('blr_category_name')
            ->keys()
            ->toArray();
        return Inertia::render('BBPS/services', [
            'services' => $services
        ]);
    }

    public function getAllOperator($category)
    {
        $decodedCategory = urldecode($category);
        \Log::info('Decoded category: ' . $decodedCategory);

        // Fetch services with state and city information
        $services = BbpsServices::where('blr_category_name', $decodedCategory)
            ->select('blr_id', 'blr_name', 'blr_category_name', 'State', 'City', 'blr_coverage', 'Country')
            ->get();

        // Clean up the data and log for debugging
        $cleanedServices = $services->map(function ($service) {
            return [
                'blr_id' => $service->blr_id,
                'blr_name' => $service->blr_name,
                'blr_category_name' => $service->blr_category_name,
                'State' => trim($service->State ?? ''),
                'City' => trim($service->City ?? ''),
                'blr_coverage' => $service->blr_coverage,
                'Country' => trim($service->Country ?? ''),
            ];
        });

        // Check if any services have states
        $servicesWithStates = $cleanedServices->filter(function ($service) {
            return !empty($service['State']);
        });

        $hasStates = $servicesWithStates->count() > 0;

        // Get unique states only if there are services with states
        $uniqueStates = $hasStates
            ? $servicesWithStates->pluck('State')->unique()->sort()->values()
            : collect([]);

        \Log::info('Has states: ' . ($hasStates ? 'Yes' : 'No'));
        \Log::info('Unique states found: ' . $uniqueStates->toJson());

        // Sample of data for debugging
        \Log::info('Sample services: ' . $cleanedServices->take(3)->toJson());

        // return Inertia::render('CategoryDetail', [
        //     'category' => $decodedCategory,
        //     'services' => $cleanedServices,
        //     'hasStates' => $hasStates, // Pass this flag to the frontend
        // ]);
        return Inertia::render('BBPS/category', [
            'category' => $decodedCategory,
            'services' => $cleanedServices,
            'hasStates' => $hasStates, // Pass this flag to the frontend
        ]);
    }

    public function billerId(Request $request)
    {
        // dd($request);
        $billerNameId = BbpsServices::where('blr_name', $request['blr_name'])->first();
        try {
            $requestId = \MyHelper::generateRequestId();
            $key = '2940CB60C489CEA1AD49AC96BBDC6310'; // for live bps
            $xml = '<?xml version="1.0" encoding="UTF-8"?><billerInfoRequest><billerId>' . $billerNameId->blr_id . '</billerId></billerInfoRequest>';
            $billerId = \CJS::encrypt($xml, $key);
            $url = "https://api.billavenue.com/billpay/extMdmCntrl/mdmRequestNew/xml?accessCode=AVQU51SS09TR19KLWN&requestId=" . $requestId . "&ver=1.0&instituteId=RP16";
            $header = array(
                'Content-Type: text/plain'
            );

            $res = \MyHelper::curl($url, 'POST', $billerId, $header);
            $billerInfo = \CJS::decrypt($res['response'], $key);
            $xmlObject = simplexml_load_string($billerInfo);
            $jsonData = json_encode($xmlObject);
            $arrayData = json_decode($jsonData, true);

            // // Save to bbps_fetch_bill table
            // BbpsFetchBill::create([
            //     'ca_number' => $request->input('caNumber', ''), // Assuming caNumber is passed in the request
            //     'biller_id' => $arrayData['biller']['billerId'] ?? null,
            //     'biller_name' => $arrayData['biller']['billerName'] ?? null,
            //     'consumer_name' => $arrayData['biller']['billerResponse']['customerName'] ?? null,
            //     'bill_amount' => isset($arrayData['biller']['billerResponse']['billAmount']) ? $arrayData['biller']['billerResponse']['billAmount'] / 100 : null,
            //     'bill_number' => $arrayData['biller']['billerResponse']['billNumber'] ?? null,
            //     'bill_period' => $arrayData['biller']['billerResponse']['billPeriod'] ?? null,
            //     'bill_date' => $arrayData['biller']['billerResponse']['billDate'] ?? null,
            //     'due_date' => $arrayData['biller']['billerResponse']['dueDate'] ?? null,
            //     'division' => $arrayData['biller']['additionalInfo']['info'][0]['infoValue'] ?? null,
            //     'lt_ht' => $arrayData['biller']['additionalInfo']['info'][1]['infoValue'] ?? null,
            //     'request_id' => $requestId,
            //     'raw_response' => $jsonData,
            // ]);

            return response()->json(["status" => "success", 'billdata' => $arrayData['biller']]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'Message' => $e
            ]);
        }
    }

    public function fetchBill(Request $request)
    {
        try {
            $url = 'https://api.billavenue.com/billpay/extBillCntrl/billFetchRequest/xml';
            $data = $request->all();

            $billerKiId = $data['billerId'] ?? null;

            // `params` may contain single or multiple input fields
            $params = $data['params'] ?? [];

            $test = '';

            if (is_array($params) && !empty($params)) {
                foreach ($params as $key => $value) {
                    // Escape XML special characters only
                    $keySafe = htmlspecialchars($key, ENT_XML1 | ENT_QUOTES, 'UTF-8');
                    $valueSafe = htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

                    $test .= '<input>';
                    $test .= "<paramName>{$keySafe}</paramName>";
                    $test .= "<paramValue>{$valueSafe}</paramValue>";
                    $test .= '</input>';
                }
            }


            
            $key = '2940CB60C489CEA1AD49AC96BBDC6310';
            $xml = '<?xml version="1.0" encoding="UTF-8"?><billFetchRequest><agentId>CC01RP16AGTU00000001</agentId><agentDeviceInfo><ip>127.0.0.1</ip><initChannel>AGT</initChannel><mac>02:11:ab:00:00:ab</mac></agentDeviceInfo><customerInfo><customerMobile>9999999999</customerMobile><customerEmail></customerEmail><customerAdhaar></customerAdhaar><customerPan></customerPan></customerInfo><billerId>' . $billerKiId . '</billerId><inputParams>' . $test . '</inputParams></billFetchRequest>';

            $encRequest = \CJS::encrypt($xml, $key);

            $parameter = [
                'accessCode' => 'AVQU51SS09TR19KLWN',
                'requestId' => \MyHelper::generateRequestId(),
                'ver' => '1.0',
                'instituteId' => 'RP16',
                'encRequest' => $encRequest,
            ];

            $header = array(
                'Content-Type: application/x-www-form-urlencoded'
            );


            $res = \MyHelper::curl($url, "POST", $parameter, $header, 'no');
            $billdetail = \CJS::decrypt($res['response'], $key);
            $xmlObject = simplexml_load_string($billdetail);
            $jsonData = json_encode($xmlObject);
            $arrayData = json_decode($jsonData, true);

            return response()->json([
                'status' => true,
                'billData' => $arrayData,
                'requestId' => $parameter['requestId']
            ]);

             response($fetchdata, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e
            ]);
        }
    }

    public function billPayment(Request $request)
    {
        try {
            $user = Auth::user();
            // dd($user->mobile_number);
            $key = '2940CB60C489CEA1AD49AC96BBDC6310';
            $url = 'https://api.billavenue.com/billpay/extBillPayCntrl/billPayRequest/xml';
            $data = $request->all();
            // Step 1: Extract and normalize input
            $inputs = $data['fetchedBill']['inputParams']['input'] ?? [];
            $billAmount = ($data['fetchedBill']['billerResponse']['billAmount'] / 100);
            // $currentDebitBalance = DebitBalance::where('user_id', $user->id)->first();
            // if (!$currentDebitBalance || $currentDebitBalance->balance < $billAmount) {
            //     // dd($currentDebitBalance->balance);
            //     return response()->json([
            //         'status' => false,
            //         'errorMsg' => 'Insufficient balance. Please top up your account.'
            //     ]);
            // }
            // $currentDebitBalance->balance -= $billAmount;
            // $currentDebitBalance->save();
            // dd($currentDebitBalance->balance);
            $billDate = $data['fetchedBill']['billerResponse']['billDate'];
            $billNumber = $data['fetchedBill']['billerResponse']['billNumber'];
            $billPeriod = $data['fetchedBill']['billerResponse']['billPeriod'];
            $customerName = $data['fetchedBill']['billerResponse']['customerName'];
            $dueDate = $data['fetchedBill']['billerResponse']['dueDate'];
            $requestId = $data['requestId'];
            // dd($inputs);

            // Ensure $inputs is always an array of parameters
            if (!is_array($inputs) || empty($inputs)) {
                $inputs = []; // Handle case where input is null or not an array
            } elseif (isset($inputs['paramName'])) {
                // If $inputs is a single associative array (single parameter), wrap it in an array
                $inputs = [$inputs];
            }

            // Step 2: Initialize XML string
            $test = '';
            // Step 3: Loop through inputs and build XML
            foreach ($inputs as $input) {
                // Ensure paramName and paramValue exist, default to empty string if not
                $paramName = $input['paramName'] ?? '';
                $paramValue = $input['paramValue'] ?? '';

                // Log for debugging
                \Log::info("Name: $paramName | Value: $paramValue");

                // Build XML structure
                $test .= '<input>';
                $test .= '<paramName>' . $paramName . '</paramName>';
                $test .= '<paramValue>' . $paramValue . '</paramValue>';
                $test .= '</input>';
            }
            // dd($test); 
            $additionalInfo = $data['fetchedBill']['additionalInfo']['info'] ?? [];
            if (!is_array($additionalInfo) || empty($additionalInfo)) {
                $additionalInfo = []; // Handle case where input is null or not an array
            } elseif (isset($additionalInfo['infoName'])) {
                // If $inputs is a single associative array (single parameter), wrap it in an array
                $additionalInfo = [$additionalInfo];
            }
            // dd($additionalInfo);
            $info = '';
            foreach ($additionalInfo as $paramName) {
                $paramNam = $paramName['infoName'] ?? '';
                $paramValue = $paramName['infoValue'] ?? '';
                // You can use paramName and paramValue here
                \Log::info("Field Name: $paramNam | Value: $paramValue");

                // Append to the $test variable
                $info .= '<info>';
                $info .= '<infoName>' . $paramNam . '</infoName>';
                $info .= '<infoValue>' . $paramValue . '</infoValue>';
                $info .= '</info>';
            }
            // dd($info);
            $options = $data['fetchedBill']['option'] ?? [];
            if (!is_array($options) || empty($options)) {
                $options = []; // Handle case where input is null or not an array
            } elseif (isset($options['infoName'])) {
                // If $inputs is a single associative array (single parameter), wrap it in an array
                $options = [$options];
            }
            // dd($options);

            $option = '';
            foreach ($options as $option) {
                $paramNam = $option['infoName'] ?? '';
                $paramValue = $option['infoValue'] ?? '';
                // You can use paramName and paramValue here
                \Log::info("Field Name: $paramName | Value: $paramValue");

                // Append to the $test variable
                $option .= '<option>';
                $option .= '<amountName>' . $paramName . '</amountName>';
                $option .= '<amountValue>' . $paramValue . '</amountValue>';
                $option .= '</option>';
            }
            // dd($option);

            // for option
            // <option><amountName>Late Payment Fee</amountName><amountValue>40</amountValue></option><option><amountName>Fixed Charges</amountName><amountValue>50</amountValue></option><option><amountName>Additional Charges</amountName><amountValue>60</amountValue></option>


            // for info
            // <info><infoName>a</infoName><infoValue>10</infoValue></info><info><infoName>a b</infoName><infoValue>20</infoValue></info><info><infoName>a b c</infoName><infoValue>30</infoValue></info><info><infoName>a b c d</infoName><infoValue>40</infoValue></info>


            // <input><paramName>a</paramName><paramValue>10</paramValue></input><input><paramName>a b</paramName><paramValue>20</paramValue></input><input><paramName>a b c</paramName><paramValue>30</paramValue></input><input><paramName>a b c d</paramName><paramValue>40</paramValue></input><input><paramName>a b c d e</paramName><paramValue>50</paramValue></input>


            $xml = '<?xml version="1.0" encoding="UTF-8"?><billPaymentRequest><agentId>CC01RP16AGTU00000001</agentId><billerAdhoc>true</billerAdhoc><agentDeviceInfo><ip>103.250.165.8</ip><initChannel>AGT</initChannel><mac>01-23-45-67-89-ab</mac></agentDeviceInfo><customerInfo><customerMobile>9898990084</customerMobile><customerEmail></customerEmail><customerAdhaar></customerAdhaar><customerPan></customerPan></customerInfo><billerId>' . $data['billerId'] . '</billerId><inputParams>' . $test . '</inputParams><billerResponse><billAmount>' . $billAmount . '</billAmount><billDate>' . $billDate . '</billDate><billNumber>' . $billNumber . '</billNumber><billPeriod>' . $billPeriod . '</billPeriod><customerName>' . $customerName . '</customerName><dueDate>' . $dueDate . '</dueDate><amountOptions>' . $option . '</amountOptions></billerResponse><additionalInfo>' . $info . '</additionalInfo><amountInfo><amount></amount><currency></currency><custConvFee>0</custConvFee><amountTags></amountTags></amountInfo><paymentMethod><paymentMode>Cash</paymentMode><quickPay>N</quickPay><splitPay>N</splitPay></paymentMethod><paymentInfo><info><infoName>Remarks</infoName><infoValue>2</infoValue></info></paymentInfo></billPaymentRequest>';
            // dd($xml);

            $encRequest = \CJS::encrypt($xml, $key);
            $parameter = [
                'accessCode' => 'AVQU51SS09TR19KLWN',
                'requestId' => $requestId,
                'ver' => '1.0',
                'instituteId' => 'RP16',
                'encRequest' => $encRequest,
            ];
            // dd($parameter);
            $header = array(
                'Content-Type: application/x-www-form-urlencoded'
            );

            // $res = \MyHelper::curl($url, "POST", $parameter, $header, 'no');
            // $res['response'] = 'bf2d0561c5593084bbe58efc63d7996278c29d503fdda62888f19afe0e8152594170605746b1f12226fb6441948ffbab45239ccacf4fff5f47c430d68ed36c57bf86f645a80ce78044ad2555f4ce7c554390a82378edfe21efba9439d0f9438c4c0c698656ec021b54bfc2a9dcc9f66a74cf5d8b984c20f587da730985c83fec1c858d0965d86b7348409c89152e73941a3f1474c3b0b740999a9d1dfbb6d34a87a0fb2c28f1b693a4c3ada20c9635bbfb988f95de0cf4ef98c51a0b0110f3d7da707ab5528d5d74ae471294f55e094122945d6e5aea5012143038f5ccffd03d28ef081497181d5f310a62ad199b366be2ba316769519a7d97fdd364995bc86060a0237b73307e991bc17a586e2197dd9db5dc8020872f3b68e76dba26b76062b2dbe37c5fad10a103dec0e985118015ed4690108b8c0b1771e5e4e20b22a22461cd2d09138c69f736a300234d8b3c33ce02b4a61751c6294483639084e76bc7132de53470162dc539b912ca13c3cf78b3cf915f70d19f4fbb4f820be23d2e16dc6c6777792d3014dbec3f53c0d8ea67b126341e0eadc3baca9fb248a476e062ec5cae742e29ee21d3888f56b121c2a849e80d55a99313fefdfa398557e9cb8fa208e5099e50b44ac5a4fecd244a41b2d18a2a0cd82cb1d01239b92a16bcfce238fdf2d2195552054eb947c388266e3ea2b0ea04dbfbeadd3488467e5d868644589cc33346aa79fc0ab2d38f3a38821a8dc01dc7521f65a08e9556145a155a645f0b9621d3f67c894d553247d34f5d11c8911b3c99275524191a7c36ca8dc933f11cfcb454e1218f5eb579761c1f29d29828cb0117e004dcbab6d95ff0407c448f1c961af9e26de81ba81aa244dd56552bd0bdb5288ed6af7c96339fcd60f5d0ad472afd2fdae7c16f34505615ef6517a5691f8bc7f32fb1933dd6788bbf3e363c5a6d7f5b61c4c60d18c6548f9f291d7aa80103d599f3de9892ef08d4c23f54a84dfcb4c9ee657cb7acfa33d45a850ab055f090bf30669fef0a4f3b9196a96e258b87c6a3c97d5c04e0da40ffa6a5f7fe7e6f834c24ff338841b0c2e163eab6b9f430bc8cd7f7b3998c5a87c9d85dc617721fe70ca87948d3152ccffc7befc069934fba9df405c939db173f127cbcd029ba5e92bdd7a3f8840d0d38dbbb63c78aba0af30f283fdce010dd4617a4d7d13d61fb68f03919d835e38386cbdce75c20e38e6b34667027cb1d5121abde1c0005b00436296c56c1edea49e6cb3a2901e5b62c6072aa850a296bbe9acbb9835d45a8a5cbd848acb5fd119811bb39dc4bd371c676d7f3f7aa18b723e1ba2214ab534753381ea67dcfef655d4326d3b37672a46423f69008723bfd42c7a0ea82db2b6d40605b1c82bbd094cfc6c17c818fc1b50c2c40e1ed91707c691effa6a4f1';
            $paybill = \CJS::decrypt($res['response'], $key);
            // Convert XML to JSON
            $xmlObject = simplexml_load_string($paybill);
            $jsonData = json_encode($xmlObject);
            $arrayData = json_decode($jsonData, true);
            // dd($arrayData);
            $errorMsg = $arrayData['errorInfo']['error']['errorMessage'];
            // dd($arrayData['errorInfo']['error']['errorMessage'] !== '');
            if (isset($arrayData) && $errorMsg === '') { //Message will be sent when  successful payment will be done
                $indiaTime = Carbon::now('Asia/Kolkata');
                $indiaTime->toDateTimeString();
                $phoneNo = $user->mobile_number;
                $service = $data['billerName'];
                $amount = $billAmount;
                $transactionId = \MyHelper::generateTransactionId();
                $msg = 'Thank you for your payment of Rs.' . $amount . ' against ' . $service . ', Consumer No. ' . $phoneNo . '. Your transaction has been successfully processed via B-Connect Txn ID ' . $transactionId . ' on ' . $indiaTime . ' through cash payment. Regards, Nikatby.com';
                $url = 'http://nimbusit.biz/api/SmsApi/SendSingleApi?UserID=nikatbybiz&Password=pjdz6209PJ&SenderID=NIKTBY&Phno=' . $phoneNo . '&Msg=' . $msg . '&EntityID=1701172112475589100&TemplateID=1707174290606277254&FlashMsg=0';
                $response = Http::asForm()->post($url);
                if ($response->successful()) {
                    // dd('Coming');
                    return response()->json([
                        'status' => true,
                        'successMsg' => $msg,
                    ]);
                } else {
                    // dd('Coming');
                    return response()->json([
                        'status' => false,
                        'errorMsg' => 'Getting error',
                    ]);
                }
            } else {
                // dd('Coming');
                return response()->json([
                    'status' => false,
                    'errorMsg' => $errorMsg . '. Insufficient Amount.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Ip Not Whitelisted."
            ]);
        }
    }
}

