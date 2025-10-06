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
            // $res['response'] = 'd1b28012646941dd58185ec704a94818f1f123087515238003ee252a8aebd06b5d6b6dfb06cc2f2a02ee374a23a1764cfd09c9245e058d5d6c2de423365e016cd21d8566ad7c7ee72511cb4aa680f125a12a8e93113daba0212966bce35698db217cac0fb8a8587251d05f9ff64f285ed35fedb93311538583018d93cfa236edc28708658af426e29ec0e18301fc0729fd8503e085f271d9cba39661fa10cf67b2d985ca5c3d29a868a7f16cd941f3f8130e7e3ba03c5168fd919ce4163a2022c299b994b2c86433f672d87a55085508fcbfb338614bd5218addf28790e05ddbe5efd01c4329339614e9b41130cf16b00aed47325c38e78c0526e713e6bfedf49e051b63130e20d4e32725a555e3b866229787b5a739a6bb645a18dab126a04eb6246408aaa01c681a2b2093a4c0d7eb9450d87e642d1f10211f734127a976b7ece302f254d6b1f8b919fd2ccdb9f7a791cca7acdb9da3d2dd86d77f95794dd2323b4b922869ffc9b8971b50bb66e88ab5a5b4843eb827756cd91110a091aed56c80e671684fc16eacd5bc00fffef5bb3334ed57dfd44523a4993f3b4d750f9387d24a756b7d6dd4c10e659a5fd8c7c0eadacb1eb9ae448ea0e868582eb96dd9c7ad2afad83ffc434fb406569aa956a7fa593275d8613e2e348331f7400ccae2ae73aae9a0e50f40c704b44d22af12ec1129723be9fc85066895b0bcd549e39a446bb22b6e7a354c9cc52f8345b88c040b401d3d15eb0b7da78925bfb61032db934f4e5a105bf18045646532b4113a6cd75ed6f46e7fb22326a697072a8b27ed75808471d800f1a428a1f1b1dc5ee135969570ec25ace4e0fe4f5078347561d7155282f4f1af5d7c2dc697ad574f878ad27b5e503893fa60561bd4d148d6e4828412fe147dd44e39853e36ab1f0d6db5cbaafb0d430238f0f29fb073a7b83947ea1c9accc282e00210e2c0f458af00566c514384398493c4078ad91028b95475bb1abfe5a4c943500cebb02ee0ef6e776a9e21f7bab40d0eadce762336e94b58b16f583d5369daf468a70a4a4c8c543f36db1b1f1e29c5b293d27d4733a47553d337ed046043ee0038962bce2f87a14d946baaa006ed4562cff0df6d31554f00514312f6dbc8774b8f12803ff54b948a2404a935859ec1cc02d7e9fd56c294ca1fbfb7086b8317a1a3b30c94fa27561d7fab2c306b5800e22220d65f6cb4c0ee9d7c50205cc71fc03efee22491698685920cf7145c50fd12e7ae356d9c0ab0709f721e23cbf111a8feceaac39d7ede7acf3e2a47bf891ed242abee3304be6f913666196cb6e0446d1318155d8f227e539f344a22cf766b9e049965a4e6dfdc35f1ed715a42d661b52212506e7aca3a30c6367a3cf2377dcdf2e9990df70805f892fe4d09ce766cc1f785f81e2576715571a5ecbc76269cccfd17d21e5a40eb576a6fb1581236e7e06be8c6e045c0720b5a68b3371966eb9873f97fb2429e3e1541900a4337bbd8eb00c6e1d08e85f8d73e39e8be10a84b17b181c54a5a9acfa1744c0f6f0a3d2f02766a8fdda5586661eb039bf4428ebbdc8fff9935c151b2f586e14445093bd9b00042b7ca99a97391574c5dd19f0d2bfc902123fc250e7c99d40f5bb81136deda0670aad2788b97a04423fcc6232ecd0edf1395a66d776c43c36ae355164d0313751241103b5dd1cb16689326cffece7b3035f543f5942d4b7131a835f5d9a30490c14d9fe8129c5f2608a4058d49c4ccec22cd3e6ed3807d8b8d05740ac186ab87020119f527ce312f90f48879412f401e13bb22f1056e815f79b0c9396e0d2294e71b6c27f6d0b911a40380daaa741586a1a3554f4010eff2d3c7859ad37197a0cd29a1d17d59a1788c7996cde3b742d70ce36a2555e82cb79e24771d1763609e40741b21298106e310a5e18615370005fe8d6e1c31783d09a588f168526300c7d8afdb3f8c1f946a20f16f76eb14a4a23bb443dc2093af482a8709ecf1f80123d7d8fd0bc9defedd313c6a69e463cacb572ed8174da9de544f6f277d8a5451981d003b73fdc9dfc04fd28b9addd4c87236df14413d352c60b660b8067686a4a3f4c0b5de84a35188a477816f2331356410bd5236bb5405d4a1be0efa9d45d728b4bfa22b518c2929c67cea1e33608825061ab6ea3ef89ce1e9422c95a6f887ea718b126b9353bcb0a1f0ebb36b6483845eb257338ae5aa23b6b07144ed05bb206f3d92ecb4fcd0a6ab2960cbe998bd6d1dfbb0a6b45d27a6019986ba37121cd0a525d850a0972a09d9ac96481664ece4cce492ecaa9e17ea05a0c8eab4a2bdf38e7bc732dbfc50b29d15b43bc2b95e06091769a8273187e76d23687f3b406b97e8357c23cea08f6c2fe9eba7ef2dff34f9dd0ced29f479451e5631ce01f948f1b346be6d55be2e758c1cfc7766f693b9ac9b5cf0b666fb73f031fa0817b61ead76b97bf87c7c2cd87c3222ac200194ca98f6c5eb51b45c80b35e997e8583c3a0dcd9a2d16f3642133715ab628e3c8f2c3853d91818aeff7410f4983f6ce03d75193fbd66c425a1e28b71b4e03dcb5f2a2ebb6654553c73370646572c8ba333e267f216bc7e2d95fc974cac8325fbe4d6a025a77f99f357b38f1760a52b67b1c67c354d5bf9a30af27dc4856fb6b5285c980e44d1a3b716b01883750b42976063a7a872a06a4a895e74421739d98081c59a99ca267bcc99ce2c74ad3052fd2a085622befe690eb7dc1b4e914037f3ca9c7e0034cdf43eb3895a77a4cc6a962793af4651736d0323f2ea35ae665477af0d6dd666aaf440c021a7c4e3ea17aec3c15a2c31eafc875afeaebb6962622b27c34467aa123a86ffe8e51a0a250cc336faa2928ea6f1f51d38e2bc339e5b39266704caf9c155383639fed5e3664276415f5b2a8a56c8c9936c7a1d0a109bdbc1ac4103498630bbe39670cb9093030d992a50387b4cab748505b125ecc8e83bd684517cdaa195c23200fbbb201ce92d0a5104f4ba47b18cbc1015f62bed584f648f888c7e80a799c7b567270dcb9adcc3e51717629e8e5c3c09c5a8ca048e5f85df5caea2041c3288f4233f93533eef6cf394c3a0547a3a0972244120751c0839fe54d7b69b8b3ac5bb8cd9a4faac8e73c05750ce24e566547fb632dd8191ae2251d5c316eaf94f8f5d8e48c24d8aab2cbc9ba744b5d8960dd22e4dfde7f45775e031b0b327d9a2ed07602dcbafdc5b714f9ef36fb22372233ea2a461cd5d40a7b7b31d543348c8b578ca8008778724bf098b76c0a2978f8b79cf555652c235a899215ca243daa8fb74f546b9f0f5ed70bb47db043bdc96e4cf2478020b621bc503f3b25e0763879b64ac99167ad999a2b32e05ecd3a88b1b8ebada93d710453376824602341512e0b2a4d2355a9a65df74b50932ec4e4c4c1516dde37a40312879fb61edbb4da1a264f1fac40b4fa0e40e921ea7a655471f0d2946efa5b979337f5461d3118490a0bfec6a07a65ae29569e9d40507200b804544fdb034cfb3005ca037edb0eea1b5e33e169eab0d4cea3953bc4405b3ea310dd7d8bcdef339b05a7cfc3adc5677e652ff5126acb2143cfa0ba32eecd447fb7617f965f626d7f3c54b479d0a6a52ddffb7e65884e754bb37ca05fb3b3398b35d5dd1f47f71fa206375f9a6e83ffa06a564ad23665c6a259f023ea4cd7816ba79756a60d1071af2c591e4429cb2378f9689bbff5a4d6a273febc9447bc40ee05b69686596ccbe58fee58872eaa1f728f39599c76ed373fb46c178f208f573875da5de61e68233439913ef33e5e8087b80947914cafc150f531c1e1e9be5a82f966431aea3d62df3513712fc7cbb4fa18a00dbb53ed079ace7b6251988e9a76e41b207c27c0f8c40928b95d744ab5422295913553c1942452be050cf92dd1a154394b849b317500a85b9b8ad89ac6ef03fe448995b5b23fd8fa1e1524adb250a46ba04b8cca012a3903b7f539d3a3cf816d1e5434d8ef2e7f39314206c68e9db6e90949da9eb3a1329fd4f9f187c7b00def860f95c71a2e21aba1fa1bef52827915ec64ab0042075cf528fefc8a63d8897f242d8828c7a17dedb7f9d26c5c4ad8e8462503dfc92354f860717c881a879af36242dea114f692149506149db2c931788643d8cff5761216cab5d23e9e0678f613a52b34f56a614d7244c475d5d863afc50494f83cc3e5699dda8db94b3c3db1f56e94a2714721eb70b8db3f05559d4b0eb2b71bab0d04ff0a659829817fb9736b1d5123f6c0ab6bab87ffc30a668680913f7c5842eb4364c2006a5e9dc0f2156e430aee8865d613782014ea6c3f0bd067fa5eb085da9191361145b96eaaf1c43afb0f506a136205921df6b96628c17dc8170299f63d617408178e7d5b39cad0d7df33154467e89f0ba7a6b670b543dbd741aea41187f2bf936f144499470638307d9c46fd14ea5ed807a77cf6833388d6abd1452a7ff71365835ff82717e4ec5cb8b30745ca20b2fe94aef5c3411e1c40da25d7023fc3392c907fd7f2844ffe7544edaf0c2324a9dfb823dbd58c6bcf774f0c5a0e5909973163e2d4f044cd9c3a54f599abf27700013950acc0a3cef0f567d5f5bef405d2a7c3a24a468abbd43bfec53820dc817b0ba0e65b45cfe9c5ee45411f98108858fd6031b4135948c232d0a4daeddb405889419b0be68abeea98612b00c49dabbefae505b5cba7b92dee9d5bb92da993f9ec2034b9fd919636aab63a1179b09e702f266853c89bd136d15a663a97dd37c0caac03063b856337a449c38bee5d86e2e791a0d511788f82547d1b7d37f17fac68d4423c52a07a93a93b7a04a0afc1ac4d1138c4c8c6c2a765c15a41331079ffcbb1dfced71608ada46a14ca15b904c44e4b0391ddafde9d7db46febcfca58b150ddf93de4d7d97d2cf09e0faf2c376d7899ca4b39d42a795f0b34c8ed6d9559352da80cef67082085e55db96d2153cb5ba82e2c83edf6cb0081218d6c29a7444a99b1e0484431fc4632a4275e2e63799cc6f990cc44413a82b446dc847ccbb91841c7fe656b8ad626a5f0459e5f99e3b221b76cd86364267822d0af604134716a32f126b6c469dedabc5886759129f53265847c1c4d52c5a46cc3a5eddcd180f1667f6af63a375ff63f618089b748a0a7cd36fa002b82f4ea4f9dc56627b3066f3a27073e266f72ecee7f744bf868e3e46bceaabcbf33f64660e056ca512530932a04adfd362e5e06b2f767784fab2f89032d1cec848475ed2c246d3273797578ad780e1259aa266a9e64cbc29b1019daa548b782c1553f200715665557bcb1ce9b615099d279c12edacd1dc0fc23b48471e9f6552d52f07d7e629d59a37727e4db7026f99e49468db23d390ade5fe6af92a2811cd38b34428558584f32d0aaf4576adf0de734b4cd0fa20415f2ee29c1de39ff878091ec90e279685bb826221d6d2909d7992a50b8cca6c848088dd18b431f88d7f627d05bec2e5c4ae6d6e734b25c635f83ed086e08bfeab9afa3e5c3364e1792062e546b09cc5d5c1050a698994fdda8d90dc47405a66f7d3d7f075151351c7ac8dded016423b0bfa5fba9b44dde0fbcddeedf27422414c3141dac01517228c21bdb429e789c115036daed78586b0256c730a544879a3630dc012f515be20a59a00ee5907aceb90c3aa91542d5e60c46a6995d09e57ed610e09445edf04bea2509f8bd226485b2fe6d4bc09c40353a999ba73dc489c5044e08dd008d629075588a10826e6c98769cbe6ab2b6e9cbdcc3d6a490dfb9cd389853f929dc0556bfdddb8a2f3c89291b46b1fc39174433605d7db25e7dc07f18fec4899f9cf43a54978aaa0ddb9537cc97304870e81fbe588e1a0256d74062a78e4b29cbe20ff2462fa48a8d36b2be13d8edb2db5747f5a879b25dd53982438e7c5ad59a24df0431cc5e08817ffa9713d3ce09da60bd49c7cbb0a050a166400dad077935d99532068bc5b8f58355c8145d0b6502cf91b8ae90b89748059b4f0683dc2dd80f13952b50233246bbc2a37af56e63d5af9cc91f9ad9446cc8ff2862c48f56f19ec1350bc735b62da5ea933f06d674d867c9349137eff8ba286b2e1203a8fc9c32d0460bc99773bc25673e4a92d36450fb135bc96f0978e61efa4c3f751b69e211ab97e245cfb92c4e4af32572c292aa8a6ef86bc65b013edebb434d75b652ac9c92a53ab11ce50468cc600f0bba960cd74359195055ae0fc59b8fb8a826e9843977ca369ca1c00924514bbc78dd384639367210d4733128c9c2c92e8515a8001e206733357f9bb8cf2e9c843ff1d9b53407daa526fc8f8f2db283991c24560ea996366f9ea28ba13c48fdeecef449af1294a30c7fc47b2563ac9af182c97c79c9af1cbd1d52b542a323de104921ca6374364b734e0d5a1d2ffab86a245e2d936558a984ea1ae9fb549ed8e034fd538836f1c6d677dd5c3bc88851fcb42048f6ce5756a8670127448774499f559eb7fbe41881edc76e5c5313ddb60fb9b8bf10b3747da840165e9bf8e0aa53b97ea94833e207a4e83aa4051efe34e0394546e0b6d841ffe6201bb965f31a1f23deb6ce84df9ac09e91a83659aee6f6274df94b3b79bd8ba2208e26de1f1e0e881fa874a43e083a58e0e9af833b7d2257dd9004fd41a2aa0098cecc188d83104d44dd89c47a5212f214e1fbe38295948e2ddae7a15dc3b3bf7ff21f2e03b57b2bb8b2213e07882bb2e0dabc6d7aa37d09a2c1e89ac227e917631347e20649b77ec8c4ece422eb0c1bedd58f1fe4bd4285b67ed8d250c175ec10ac70d95e8304db1e8d2e111cdbacbbe8b3d1027308f5da04251bcedd12c618032821f9e45d67ab16b33ffacd42548c491';
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

            // return Inertia::render('ProceedtofetchBill', ['data' => $arrayData['biller']]);
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
        // dd($request);
        try {
            $key = '2940CB60C489CEA1AD49AC96BBDC6310';

            $url = 'https://api.billavenue.com/billpay/extBillCntrl/billFetchRequest/xml';
            $data = $request->all();
            $billerKiId = $data['billerId'];
            // dd($data['billerId']);
            $inputFields = $request->except('billerId');
            $inputFields = $request->except(['billerId', 'latitude', 'longitude']);
            $test = '';

            
            if (isset($inputFields[0]) && is_array($inputFields[0])) {
                // Multiple
                foreach ($inputFields as $field) {
                    $test .= '<input>';
                    foreach ($field as $key => $value) {
                        $test .= "<{$key}>{$value}</{$key}>";
                    }
                    $test .= '</input>';
                }
            } else {
                // Single
                $test .= '<input>';
                foreach ($inputFields as $key => $value) {
                    $test .= "<{$key}>{$value}</{$key}>";
                }
                $test .= '</input>';
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?><billFetchRequest><agentId>CC01RP16AGTU00000001</agentId><agentDeviceInfo><ip>127.0.0.1</ip><initChannel>AGT</initChannel><mac>02:11:ab:00:00:ab</mac></agentDeviceInfo><customerInfo><customerMobile>9999999999</customerMobile><customerEmail></customerEmail><customerAdhaar></customerAdhaar><customerPan></customerPan></customerInfo><billerId>' . $billerKiId . '</billerId><inputParams>' . $test . '</inputParams></billFetchRequest>';
            //   dd($xml);

            $encRequest = \CJS::encrypt($xml, $key);
            // dd($encRequest);

            $parameter = [
                'accessCode' => 'AVQU51SS09TR19KLWN',
                'requestId' => \MyHelper::generateRequestId(),
                'ver' => '1.0',
                'instituteId' => 'RP16',
                'encRequest' => $encRequest,
            ];
            // dd($parameter);  

            $header = array(
                'Content-Type: application/x-www-form-urlencoded'
            );


            $res = \MyHelper::curl($url, "POST", $parameter, $header, 'no');
            // $res['response'] = "d1b28012646941dd58185ec704a94818f1f123087515238003ee252a8aebd06b5d6b6dfb06cc2f2a02ee374a23a1764c1a869dcca03baad59e178d68114572cf9df93f9c9e1fee3cf78f1a0d64c3e959cf7b1e6cde41c88cf055de876d13fb5a7580f95f77df36b84ee1992633270289ef65c290a7444f9e7e2e5098f6eeae2ed75df56a114fb3b519083f8678bec5a855bf30f5964c94d1d81b364269ebffcfb0d3fe8f7031dfa716507c91cfb4f83eeea18e867718a884648910332af75eba6ca5d712c970a3f70e6111d78f762618398c383d34da55e41a88693d3070ceb8f60a387c70050e564ef814d5dedff0886073dd0f8e6881014175b429e68711b3ce36ccb1676a8c0ca526bab897b05764fb65dae7b1e3a8e9fb09df5c92fd9dfaf2bb8156f9c6bf03d4e3461e71072c351650c88303675935093851f55d7384348f7dd3958df5457935703675edebed6a878c84161c5b2ee1d4928e68858c3b39e9d04d60fe931be744a43350b2f545c0d1babe1fa31af14d92d25282660a918d75d6a80af05837fa5ffc4c42a830508d5507aa75191e4e406aa842507944e3e020f938d2b29d01cc6c11654dcdf0e266e0c6b074f2955f340cf729f5e7465f6957d810b5395f5b2793784bba4c6784b758b8e1fcafe70c7e0fcbfa09c5e75294a98993b71f4a1ac44d05cd23bf0e15218d1ee5cbaf17228b1c9e73f0d6883ffadb251387198943d27a8ab8bb7e5e82985d2e8880ff310cd41f1ed64bdffdad1dd546a962454ce4fca67ca9edddb1682fbb964e93590eecea2e11d39805f771b0f595d0935dd9b00ea473c6ab5b7b97099a29b1f7ae3e29776e5ca212de59128b6e825187179a3ff1ac8278ae5b39e85210ac0192a02ff588487a5d9aca9d628b9e12d2d349785d34956d6e76b8c08a31";
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

    public function paybill(Request $request)
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

            $res = \MyHelper::curl($url, "POST", $parameter, $header, 'no');
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

