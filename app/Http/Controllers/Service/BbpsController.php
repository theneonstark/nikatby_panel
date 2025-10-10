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

            // $res = \MyHelper::curl($url, 'POST', $billerId, $header);
            $res['response'] = "d1b28012646941dd58185ec704a94818f1f123087515238003ee252a8aebd06b5d6b6dfb06cc2f2a02ee374a23a1764cfd09c9245e058d5d6c2de423365e016cd21d8566ad7c7ee72511cb4aa680f125a12a8e93113daba0212966bce35698db217cac0fb8a8587251d05f9ff64f285ed35fedb93311538583018d93cfa236edc28708658af426e29ec0e18301fc07293acfc6f2e430c660ddb00b23af795203446c85bbcfe8ed1922fac9f8d145a702c19ea189379a2701e4d2c78484827da9dbf681eb59fa918d726065e6d9812a10b7801ae2908de3cfdcdd1bf0c7f9c43350a1b10becd560bcc5000f11d9ba3fa823df0ed46f6fb8284ad752af4d82909cbf8861ffa1b7d58e7486cf442f0b5feb09781d9cd74b6d0e8e202ed2d329e472554e3aa0fbb06b1fcffd0f8cc8921db69aff68ea881bee289cb052e77fdcef0a232efdb6b55ed31c083f141567b9596ee715c05e6cd49d4b76655e059b0d2e62e0ba33d57266ebbcd76e5bb41bac79d5449cd26e4fa12b2c3128eb68b221a422206947ce7c467b489c1ebecd22dba2c343d921d62c46df1f22b6dda4d18ecdfd14f2083a0be494caa423d65ce81edc17ef3ad235a664cd99a8d9d51ca24b2c8778e04661d4712cd30165c050ea65465f655a2a96378bc1c5bfe50c5fd1c46e70d76048352b2544ab07c75694bd3284c6a561d2ee9a688e465ad6b10644136edf16833feb09de4bae97503c3c56f3ce8e65656457cb2744984828ac22acaa7122ca68e99d986900e5b5d7fe9784359afffc7353b17daba8d3dc3feefe83ff29583370de87d2ed13b582ea91336432cc2a3b494c443e816bbc7c6ff087bec6e4ac7ca0d319f231dc6a228881790bd2ecc5f0e739c4fbaa601aa3c252f05ae22da4d549d924c84ec9a7e27ca94344bbba452b303a8d92fdce77620f32d04349bf4395f9bdae16cb97dbac47e002d0e36ed2a5ba95523a66d1c1a714ef317df1e80efdb274faa438beba2b3b186947b410f8731bd25d19c4e6ddd81e3f99088bd01f8c4e45019b540ef11793b886a65d8154e44c74772a13ae1a2800126e853b0b5b81f86d292d4cb0b887725950826bfe12a522270b8c2aef8389330412da19ce915a9d3fd4d90887cada64e85d6fdae77968b431d296e464052586595ef8b375ca32f8408036411b10931b6049f8a0c25d02a2f1d51613521219ef3ed4939c0888e5e34be90ff6ad79c83bb7c506f0305f93835a9723e941267d5944b02e41fc713fca94f31fe0059c17af20ebc60e328b7cd717e32d8196a3a960bfd95fd760f6a4eab9e8d9e72a9cd09cc8d07da16cbcca49893595f30306cc11934d651b1b2c4c8592498a6fa58ef61775ffd807e72bcf8a21e39469a1a175d58b9fcc2b7b2461fcdeb29c262049501f09f48780e99674f7c4f463f1f6435423976653ad9921b63b28ec8fd888dc18afac4db0eee611ad99a63e71cbb29f373ac3830ad1c4427b3077e12c9fc36270dda31412dbc66f1f264c9523c7cedb6816795266ace9eb8755cc70a49a54db2ae2502e815a6d91fd24268f74deb31d11c528b72e278cb1440964bc4d6f4d667c7aa605dea7eafe44ff3dbffaafbf8902f88bedba54da9a00cddbe69c55d8975ac98336b83315607f91c1ddd5d7805ee728707ff5c77f28b3dc0b54d6b4825ea6ff84ad12ad1c83c1585245e2dfad8b884f935628ef32308697fd3e2d08c7cc3e9b1191da886b599046ada25cf28627ce8b751654d967568385b9f366dd8a3c1d36afb4eff5c4a774e79e306cf11326d960c1fe668c210d6e97623dfae68be4e6a7d2f316ac1d8807ba6af26e15aada2842ed1f558887f1404d58eb69cefa666bc780eab07121597c0235a57a1a0f152d7a499630243dad56b477fb9afe18b5641fb70180562dc64973133810c780cc732303e99773d9da9c007e6e238b06944d69552c4a0dc84bfecf13b1a2bbb21eb2d2ca173995ba6ebb5411cab9a83345e8642ee6050d282c40a11b6d92abf56c6533118653d0192bbd7d213e92985f1d64fc2b2506b0fe5fab09af6617c37fde534e31bfcd7f67fb80d9c88d798a2adb0ff0ce9c4ac2de814e5c8293993e6e99815ec02b10ab79e923a89641b734e26332628051743be120f9cb5492da8f31490e252019bde554fb86d4b9cc3f724640039ca0f2f9a380550162bb30120cadd4281f7aeba0d72988791cbb32a58216836482eca0ac69135be2dabe98e88e619fc0f8e8390a54476aec9292555fe26d83a95bc29bfcd699eb3fe3175eedb5a2bced10641704881f2723017e0a58c96c1556d1cbf76417412e5d8896839224cbf4565426c4c70cd70875ee65c8b45d48f644725ba2d156eea925b60bbd12ef2adf1c17099cefd7b8c9de2ea5659012eb66385d02ce437a2f266bc4277629645203b14598f1baccc2a12518ca1b29928ee45f70293575307598fcb96f0cd39cf7ac175067924e79dac848a61ecd4aa291741d6483bc80e91668dc93dd820c62a8268c59e9ceecc728de4bd0a8ff89a930ddb5da57bac4e6c5ec54ac9756164d2c6b1528924e414729622a2792c47d1c15d5621f784546041303b3515bb090e03491103f953dd40472826ffe444797a686054c73e78d81ea4598f7df434dac1c5b1fe4780f8b9cb9f56a0fa5bca773d0f6fe6b27020736e7f034773fcaafc7556b1ba291d3c1007de5d1e62eb6a1fd4df68769cd6d66ba144354627ccc3305cefea61882a0029ae4bd4d23310c625da9362b33180aa08420c0ff81e182a84a06f74de9fdb62b749309cb06e7820f908431d4f328bcaa301def9173d9d25b94510c6e62c86b28fb1237e24ba789e2dd8ee7b5b13752c65ac912c9e0796389db4f1e2d9b5fe37402e154d8ec9840d9883239667db399e740f91fa7c4daac71435536e1f8cfe3a565334b98f96a6ce33d2404033fd963dbf89efc14e9bc75c96fb7e5bdb54ab6942d6f35358dd003196ff38383b446de31d49ffa013f3c08b338241ce9dc022e8dffa9dacd07ee30d5b6e9d00b681a9103dc23ac1ff88e12bb00b8c38defaf8b919b77ad54cca56c4d665ef227e9a0386dadb8f4f49728ab32c8cff922f9cb662dbdc7522eacc892f50b9be4e81df98b9cd10828186611a5c1578da13b3daafa9aeec4d4d9c2f4f7c73d459c8a97dc3f345c42ec9670c93ac42eacf995f354544ce85884c33949f259b27e9ea2ca295cd38dbda8ab17ec0cdd051ef933bf2c0873f694f1f1c45b50389eaf288ae83943e16ed1cbc2f4a2271fb22e782cc22ae9bd47414b483d538163c6f4ce43a3ddaccd7eb39a3e1e5e2ee8827eecef05a526321022a5ada96826c8ddad38b30091ccac2c6a886380444729189bf85dcc3d8a375a5a67c2916119c6b49e7772c5439243c8a1959ec262ebbcd8498a6a7480857bb545ebaeba8a4ba5b85ac4033049fb1b6450b1f5cb9891e99d4a99405cfdd2a57e52c5cb801dc094c2e16613b5b72efa1c0f90f472fb8b9723db73da3647231c4756542b2e3d3154f7d05ed4b7c094fc329c37d751cf30e06ded101f47c32cd48951981ef875091f0775474848d8a2db06f7bdd251116bbd19610ad236887bb0abaaa5fb9d15a6aa0839a436c53123ec22abb4883dbce31f1eae3fdaf635ba688a36d2a5ebd81f9cab34791453038f9d59563c577df01a257d85644034bb7bc6c3c2a1229bab8b1fcffdf4db9ebd859c792fb8af41fe0be93adbf544675a80803ef569a7b8b9cecae5639504a75b7243071ddaa8af813bf2a5a162aeb413c9ae6fea968c16b6fb69671bfd73b1d3532d3d14e87259e353909bc44fdd1a157e3e81bcf1b0b55caf9be8741bdc9a098a4f95e0cbcde3ac92c7cf619b555c78a75973747058abde6f2fe67bb950a3a2e1cf42c3692938a4319d579cfe6ed20bc0abc7d5cd592d039b182d834c01cc75d2d6ab724901ab6a44b69ca84e49e32780c7dd719dd695476242228cad33b07455a535993ca6e0f9a5fc943117a58a8e5268f00c65aa385af2e94e00713c746dd4ea6c85fca948995c27597ac3ea46c9619cf84ec0fb88491c9e127eb6661925f1998f096aad252d8c3a0699d60deb6098fbd527b94d3cfbf801d1b14ba4c7b1c9b75ebc4378575e15c423eb68a07b0b33e9f1bb0a167fb80a79e3b2cec54694b795e9ddae95337f59349bd65ffa83fcec23052618a3a13832221819726484afe8d0de8a33162f1a6de15b0b7ddcb72db80f35e2c0cbe597186241d0dcb26c6fd49d3a0ed4e1eb6dd546f0b4d2e0884ed0bf3f795a446b6d4ad5f1ce663ab39ccb3f79e4cc08e5a2a8121c15710e01d491b6c112c7319d6be839fd4e53f0e7ad2327163e12c9c4708db8026a9cc2db59c3e788618e9edc73f9b746ef404ffe2427d68fb92284dfdd8573d3947906209c52ac097959b1117c799418c2f1e73012cd58c0e65770ad5a183461de607967e0b9e651c0636f8e84cf5f02ff073c0c1731ee25c7ba735106983bc83e001188db525db1d9c77c55ec0013a5e0fd39535463df556406ec03af731e68a84dd77f1b06a8b54fc668349272e16edd214b1b2897d33e6662d5d647b391e662e63902fcf844fc9dcf57ef3079d5ee8e636fedd4612a085948a8f15cffcaba015a0aa627e3dff0b364a4b5d7b87b1cffc34981803394d954acffc25b6c7f5d4e938321b0d7137293618f323070f795f3378220cfd471bdd8d37bac14c03197834f57dd8ebcfccbe0f3288613c087e3d7568c3e14b6d708ff2c8b2f95cf3338adbc1603815b86fb83622381290c397e09a6897b456761b718dc27c090bb03326757c510e6d3a52b5e7ea392c86e70c332b66ba9d077fd64dc2692243476aa5daf09af56cf989875529890c6ab61e3d48676e3b6cc0fdc63a5d009a6c820a23b496cae99184422cb3d2443ad7abe012a4989f14ff48ab3f8cd028c2cca9b91ab8402847437a0fec3fb2f39f31ad507dbdccf09540214514c227b1956cbc9064692c1988201ece6bfbd2b782f6e91cf9c1875a0b824781ac0d31dde32cfaa677cb9a36778b5e58e3352d8d42e24c996ca1fc46bc616262036a9deb705db09606f68baf8de26287e37bba9b498ac710f3fa06ca7d77610dc35db703907108fbf06e85e3e3945efca56c9c89d038a76b406ff0d278e14431661632872bff4f32b63a035ccb2f4b7add23950c5868f7757f2e861c2542600fad7371be013abd0a704ded9333abf75abe018e95f85c8eef693053f9a68404630f409feda8de7b4ece0e5163149e9d495e08875b7e732822b3609d1223ff45d193fa25cf9fa6b2907866dcd560203f44c8e638834718fcaf0b380c20438596f674f5c6d70293864996170b1a6a22a1fccf2ceefe968e0cef32fbb73456ad16b72b5e363df15833f0b89632160460687e5573aea275ae185483b247ea0b75f25dbc88c7f80b0d78e21c2f908a4ff7f48458388bbbf818b4301db7da92d8628dcfb9b9575011d76af4aa5164c8c89a3a52d43964fda62fa2120f9ffcc32386ca7dd8e3414fdd81ec0fc629e500236741f2a5be6a6ed0ea9d3498e4e4cce66224b0c641283e02408d03f5406c52428565eb5b2eec655e8efa9748e89dedd512e98baae8d62f03d4bbf30a450649663671040e442ee9c67d3b064b84119241d83a5df87d772ae13034d1cc31f2133703cf36b993dd8b0125c24786da6241da92f2f447058f7cdac182d420bcbb4a0bf9ba48ef9d9577f4dd272007e61019158327880fdbe3a7c3abb49dbc15f4c82c2c00cd21379132f9655287f75173ea176957e1633d535efee20b1e3fae351fa5d466e6250b215c4301bc64532f09be0617f252a394b931f031ebbd4e56ec497acdc1817e5276363b567bc264a02425bf56371c49de8e73465bfaea4c9c2cd64622b9782b9fe00a01fbf13f14a1c809fffcc3b95c02bfd4501f5aa117ae3a44930024e5f6a959200a4d99de8720e3bda30f4efaec8e38ba8b4e1543c33b64983d3706de9d2a2f7378e122a35a55b1ce0cad9b38a80f50d297919bc4409e769a013f55c68b0663cb5dd5d373c6d1720d85c06d82923b703153343c25ee3ff0cc71a847ee78cbda248481299df789558b02257fdef2d846d8e78d6287475ca851d7f5fd622bae0805a05b05ca0918ce4ea745d07574f075648a2f86fe60ebcd92c096c69bbcd74907b88ec7de4d5c0e0a36c2d76a93b1e14820a9e14a228f7e0702a400ca59ea9772d1eb5175247d82767e6c106bca59db80bcafbb71ad5f9e8a6a8c845c5d0d1c37692eb4673f25d586e7bbbdc0db8e42ec5767089b7d32f37b6f9ae216aef89d59f9e843268b838f88f5b9fa8e3214ce57826eaed90ed3152a25fa742838c441a2cf1be0911f53514893b30aff7bbe0e3f1d1340d3e0f91459c98e84e1b655b154787c00580e4ebb0e662440072bba35b94b589994d966c7ebf8dd43ab66011c49ab49116a68da1a52ffeac7cdc1747af5d639a08874ef528e4afa2b59dacbe4aee6e25bb241efb3490172dee286bb3638aa7fc02962f5c5ea2f3eff4a4c4547713dfc4c9c6659cbbdce223a0c8e1ab4e8d8622fa9636e2560b30c17396e65f27466c6224768f9062aa61ab12741a7f14acf4b8ae172bd67030afd3b64b5c474168bcfe2894c5d2f9aa3e51d99cf352538cc886cec3bddc7df295d5051f2b11e333edf50cb29a2ad66239e539f233d59e018503d73d3da3a2fa9ca7afd4649c3dbac459ec2e1f496811ae56a460f73baafff8115a7ddbeb732505af84446432e21b2441d7c414087696030d80cdce75fc3dee0eae7318e7390b6002b72c27b5b9a7ee0677d21ec707d2fbd217438a081b4bd51487d69aa5f7707e650377475e5ee475553358a8d1a2999af2841a7f5f4449b8d77f038ee3b3298b91ef195b8ef5e2e396691a958de31694ea0f297694fec74f849dcf5ef4a5634f07d2a87b5ab08abc710b0cf70da31c9ad082473999ad300ba94801b08a1ab73f4cf11ba4aef69538c57a340ec80a88cfef40a41adab99f15394b33d5572243bb99a8153f94ed00c0be7cd6d98a7a985d00a043b9fb5be610c9caaad0df4d6a2182298857d7e702e835d2492ce941094182784b568da5b1b3073e0efb0f21865f2d3ccb18704afc7b0b7024ca3437c5fedc43b0c9763afb7c4d20c4ab0fce579d7b2f26c5e44fb133481933a1f735f2735a4488c2c25075148f2d5b233ce5fafc8a8155b29b4dbad30b4b48edb3ed865a2786a1d2f9af1c0fc4500ab1fd418227c9788173a6ae48f8d31241031c18a1bd93571bee4459f75d964be3da6b00478a9487a3cbb7e0b0b3eebaf64d2a879b2eb10548dd8ffa9bf567daea9d77a0659d386408ee7d48c8edd6209d3bc3e138c67e3971577e089a46a1a5be8351c00a13f7365d3552f2faa6ba1642111a0d924dfc7ad60884949bf34fbe14cfb5be41580f209922e0160c45cd9962d6c8ad974048c24e0315dba9b5b722eafedd1923c9fbf701bf11a44039d72a33f5fd6fd7e4a5f038fd2ea60caa0375f958ff5678b2178664160958177099c69d0c6b65765727ab8d022111b44bf650502dba03a1cfc83e2f7a8b81c73f0a9ebd500c74b48816037ca53ff66ff98dd8990cfcd7b4351eb1fa2fc071a5d97718fefd54812743f1c39c7bf63bf2c3b66014c43e80cac285d92ee775116b4b1a96cb6c44c045c3c554955bf8f256f4bfb54ff4531c0fa0cdc4ad0e592b7c09ecbcb443b33c3c71dc4287b166e6f267dd731b2e35fda8ef038b59d792aa9137324054c202c2c3ceaa80838512f24b3c8d8acd0036c1816979a529126d872c84193050074bf6fec669592aefa2898b2fcfecb3d5f54d1a067fe92bd39b454bb50511bba176823a831735119358b8257f8656fca40bfdf40b791a4f0ef17215f11594e5aaca51f37badf4d7e6f139ba09c5c9b5f2ffcdbc1d2cf7fd28ca66b451661c9bc749ecf3590ff522768b13caea8657f8a55071f68889628f9655df3d76e5956ca8e677479c094454015ae478f6df42a124b60cc1ac5c2eb2f4d0845efea65412aa2b31cce759077aa3c40e7743a0ccfaab5a01be5ba49ca9f3201dc6722d69a21062f3424120f79120cda144466adb166c0d0b26ca7c4fde270529985a2c60633fe5f46b99192eb024556410fdaeba0b333717017817dd50cb5488a36f51965424b035305e553eebb2e59cdb0b34a5773759b1cb091edff6f6fc35f663cafc83c4cf2e0845dfede8bfb3b9bdae175ed58af7856ce94d238ae316d471722c2038fe1154f4e9f43bbd67165f59d7a28b45e8197bc6f7bba6a1d9c3a6780b65a32ff00ea61d619e0160daa0ee1d0bbe980acef4701a8f27cefb5aa3c9c69121d9a74055b9137021f8ad4d224bbcbe0074198ada00bf04b7898c37d2969a46f46a18fb80624dc4734b448681bab80ccdbd85913b063fb78f6e2ad484821872f8ced4954260c8e1fafdbf6f0454166ea300b05d7090c24607afb403fb4d4f38ba6f53d79f7521850f30f6d536e6da4b8181f7c6d2a4ed0d51a47257d688958e0daf07af48c6bdce411c53ee6059567e917d009744f1b4f4ef2cc3f5ffe4ae86f84581939e882c34e263b9fa0b3b689303b19af6f9cf3590609c7c6c33421093df86561439e23bb155422962b2e3299454fdbb49d656130a9bda36afa3352762cd9d1c19875ee8d9e8a9073767cf9586aef6cf9abd3e9974e3292030149282c5b0fe37f0dba9fb802f2b71aebec5043c252cfeb03b2aa35a6889ace069e3086122442bfa6ab2fc3e6a6bb8bebafeedcf421c2c45709df5616e4b47e338f4c002d14ffaf5930ccb13d81f1532a4bd71cd1999bf12e6bf16e6c328a5642d4f7e543777b9a0ed15bd31a95453894e8e28220af4ce76d92d5fb6842c9905381b12d4c07a76f3f1e9b44046455165646342f479eb3d949c060961841432ad481ee1f35a171a7e0415bdfccd5291e3698dfec960781caabf7fedb6083a29580cfbd13bd4cf602322adce3a5b80948a71bcda939e5faedb0427eb632448ce0434bb37e856bf89dfd3d8b265c2be967b9ed0bf03c1af6f68bb11e92f7e29beaf906d1e7e6051642d38c5cec3bf88d880bdacd6c78679b79a85cdf27b9bfdffde800548d66da0dd9959225dee2969a393296977530153298b7e78eafb94d0ec6daaaf714ba2656907ba34b034ab36884b2deb945b277712697eeb3a7f60b73f16e7a9c8bccee45c9abd9b3a7527ab2b4445c4b5200fab71689aa86ae9cf55b81a6cb3806d53fec14d505d5c6a430d7ffad8cd837d1eaf0d142c051c9b41c814599d8f3b191d0a3b383996c5ab2818a78ae5e8d1a1c4cc7875be586ba6865e6ab053f419fac31592db53e8c65af44d1215012302287d47291050b0a8b34cc2a0e0d7a9e1fbbcfd802b284f1547432343a78a6bc5d5bf60a08007968488afc9271e665e047f454362b050c10a1706da86ea542df81fa573266e3de3563448e8eb7e6c5d15de1030e6d4c957b464bda663e021e8c6a4a36fc18b3ad67212a553833e3bc974316a346c92d606c14b5e8a20f5d28852da26cd0de8b49b17ccd0bbcee21575e26d21efe8790615b43c5d3414687932c993df2d9d1233b91799991f5f872ed9ebfdb0decc5da1a4b2e83c0f953aef0c82aa4cf510d67578775ee2b0ccda6745ec83dc51f13ccb0170ab76a801236790ef59de66f261475340aa82be490dfffb5dacc527d3c7d87ae4c6f70b77cbe6586e8d6606d1506dd6238467d29bb1aa733edb9ec5112dfb38f9a2667655d358785ff3ec2a1630f4dea7f42d164390138c719d93dfd9132a1df0086590a94949424002c764ab5ebf68284fd7058456064203535b3b7216a1011f010505bd717e98e7226147c4795c4e1f5b00efd8895565e813b7abf28e73c9fb9f2aabed8920775d08eb37d1494ff4cd3cac4446377633e89c7eed04732133a56cadbb9bf37b648ab1afc0f513b3be5c5a868171259515d39f1aa4751ca5f7c0fbfaf194251e0d9bfba2caff56b831ff5c7dab235726c694fa52d84c7a909227eb46154f4c4700ce422cb78b2df0c1e5896e74a9b7855425720cb7e4640c2245a8c90418205efd0aa3c6b5b3de45eef1abc4cde4c77c130b33c986bf1ba58baa4e65e0e091966b89ea71c52d68ad57bc6846807315a39587d52e62e414242dd1e47ab010408f7b437af26ef3c185b3b3287b3e54106b8888c892876abf5f5921e9ef554a135fd7517e87b2d014026e033e5a91dadc4fc4f22703092967b49cfed9323bce5991b1c9";
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
                $test .= '<input>';

                foreach ($params as $key => $value) {
                    // escape only XML special chars — don't touch spaces or characters
                    $keySafe = htmlspecialchars($key, ENT_XML1 | ENT_QUOTES, 'UTF-8');
                    $valueSafe = htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

                    // direct use same name even if it has spaces
                    $test .= "<{$keySafe}>{$valueSafe}</{$keySafe}>";
                }

                $test .= '</input>';
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
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $e
        //     ]);
        // }
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

