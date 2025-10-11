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
            // $res['response'] = "d1b28012646941dd58185ec704a94818f1f123087515238003ee252a8aebd06b5d6b6dfb06cc2f2a02ee374a23a1764cfd09c9245e058d5d6c2de423365e016cd21d8566ad7c7ee72511cb4aa680f125a12a8e93113daba0212966bce35698db217cac0fb8a8587251d05f9ff64f285ed35fedb93311538583018d93cfa236edc28708658af426e29ec0e18301fc072907d9efa2d1f73ff97caefcc3d3391d8674ce9bfa7ec54bc61f68424275a07f726308acaaffd383462036e5f2864c8f2156d1ff85990468106ea3f44488f36743a6c7108c00065359d0efb78ea64d5fdad21d7ff3bfba00b3f8453db8456794b8e541f3410051bc8c35062d1aa9c2fe932184f11f7c138e58d1dc56f6cdae1cae045466127ed284e83c260949312f5f489b518ebc9dd1ca002a0bfcca2a128b25872e1dc7bc9c60d93faf59b11ef7fbae8f36d2e2c52e515108f4634a600847a9f6222f1910af2b58d71d83446ae5494879573f39c8f5a7e479d9883bbd49797d2a7bcb3492824695803e10e216caff2f5068f151e7b6d03782ee4e601849c91a867f46e87e081277b21c785b2c21a6bd493900a61bd93f99eb2e502ec14895381f89b41b83da686a0ccba4f6707070c58dcc40236e12d313291c10f17bb433975fa0bfe61230335cbb186ce74e5c1af8071f7d8bee024832d9d17e35e81663e04d4da06a4e51666fd130d2d08dd921e30fa5d1279557246c5963bbb00de0016806c925d86d95b9f9342c896ff12068da76d4178d09f097eae78cbd282f2f7f43b90875a1ae29490471e0e191b96106c9594be71fcd00110a9b53b18a5998eccf71c4030ed0b029a32a8d24165d15c49f8c3291b3b0a1d00f5e7f4e3c325b8c6ace2e645f3434cce799b7d82ef744f515b98213612eb7a6f86d46a2bed400776cce31b1988390e9a96e1f61b0e8386e7e2d4493f26cd7e6a9aa6322b9a9581f84fd29c90b5b28d837b77cec90a501412d1c30b4bf80124ef3cf8655d04e487bcab84c7cbc65905a3f4327bc781602fb4d80b9b663216ce324591da36b1064061b85887802d55754539dfb96cec079d0a4757035b081a7ed6b81fac7fd7d0c7e0c828449a8ff516f3b4181ccf4ca20021dba064c08da0fd0c5c7507f964cf7cb97a38a65ef3a66af2be8ccf89f3b9afdb2a78360e27e76486558a1e34c2302e39187c4057b97005488119701352fa8ecbe27a652968b0e6c5cfe124fbca38b1230e2cf6931adcdc6a21ed01453401b3c4a76bc48247a37a42e93bdeff1480fc63351a2701c9a9d0ca728665a18d68b2f428147b52f87311a8b34ec8fe13dc4a8b63b5b7237f840ed8fef30a85c0bba8a00a9b6800ee370db15a1511443217d1aaecc94a5bc3665c033fd9b9740923c7d3168f3aea5b9a4b9e3072825f4594b7e4f88e59eb6ee06441251cbc7e4cdd895396db6bf976881f2a96fbc89765105dda158022d601fd6baa1eb869f4cc75292e41eff9ffddbd38f75c51d9f2482e13e26f4a12a124a0250e3fb33247c98e2e4a1da3a2cb842c183863644459453fd6727d974bc8dfbaa1ea473500cea48fcc66f85814f8b12e0d75f236fa64906cfe857a9b5922ba1433c604d532eda8cab4293303908128380ebe97937faad0c40eb4b751c5274af9e6463e4070a011271e3ad468d7690b1b812686ba87d91fcad49c6a8cb88f8de45f8fe7b49d6357245cf2cd5230f895ba170d89d9bc464b11dab37586d1ac5a963f5d708e89b4a2678dcdf95be38a025c5a7903e6926141cba18eb0cdb04167d851494ec6ef58453ad6e93001abaed8eb154b6ffa59bf3b5cd2580058e41d622ce7f2ad1adea0dd1b6550f1c030af28f5293e5404e8ad0de84c5141dbdf468768fd4e7f485e3297ca68c50e92ad364cf671e4e78a36deb15575fe50a274fdf35ce860f159f4c7364ea0c54b37a8f5ce2ccc6d362766263af8c4120e77169d728e267e2f476ef5f7bca560dc751afe480248d439f96ec2d2bc60df7d2eea13654cc929a54b3d26ef364264f9919c1d4bdc334b4a1011d38641e57073ab9ceb729215c6709a204abc35d0f71973cf9b12977202ba3f2d6a50871d4965764e80fca42d6b42abd7c1355544389d726ca6a6564b240b2913c31c930ce1a96a604f90392260003f11f7499503445d7fdfd9144b6c2a7ceab4cd18cbf967955c938beb98d979f6e8d8445e64c140a0c6be5e00709552a8fb55399d9723edba3394ce7a2d63a52084ff63a7b5d76be926f3acc110f014522f3c6ac6c52230909246ab2bc2c0f13725afba204a883b7307b53514b508eeb06fdcc075de99c1ea9254c0cc6487e7e5b77b694f1ae310fa59e09a6d7283265fe025983f29bda4914de68bedecbde3b94ff8b159e5894bcb93fd902974da2c3f24f105198868732370c735f6e56457b0c7b3e0d46c3cdce88af97133cbf52a05f550a6f67698a9ff8c1a21a35f2c9396426cd836aa988a0bd5f2c164b8a86f1250c969c6199f50cd131c837613c76c351ea3dc0533486f4e0fd9556a69a64925b0394897b0e18cc8ba82c980fa26b4a92c6a340cab0d781429d56de13814f7ed89596356ff4ef1206ebc9baadd9c4625ba429e01596be42499bb52d58e46feca2f75792b16fe8c41f1c37c5ee58f5a31fb90bd0ea09d870be59b498c875aadb624c71af45805a4ab183792f6c2de29b6e036175455f9a65b5d81ce26c4ac2030be2d6c415f5482572e743ef9fcc436f168df01a425b59196f78bc2c009de4db0259cd8d4fa89d6610cd165c4e724b5f3bfe976edc0fa2d5bf9164b2d3bcdc1ef81fb432441a284c1399bab20b575eca1dafb7484d01d1d1efd73cac3465209f60ec1dffb16896fb2e8c3c6ccc945524922844a29f22e0af4b1148c68a719e0e00d98cffdb2260f431450b79d94c83b3cfe37153e3577da1460f8e36c7470367bd5370a0a39c23f874b12a919d8e44d663e3e3ff99e066fb3504b3361e5afdcf5bef09792afc18b9271b300af50d133514e9701fa5994b2bb5d57a50d33d54398ffeb235bc5cd3add19589ce5fcc074d3c073c9b10bd9412a3b37db862e0c1b7a4b11ec419565722c9acc4858ac2ac7449cc434ab9205b5a07c78db087cea4fd7c24bc0a12de41492ecd6c9cff82a98640f37227c8ab4231b19c2b909c57985202fa13fa79682da62a381f6c97f2d32c4c6f7a8fd75cdbb7af905a0beed2657fb7462b0f899b5b17bc7a50cf3894d4cda7d9c16ea3a5f95bf98a046b65843fcad5a462c0e74abcb91b5729bbd3461f3f7ac8f6e6d1c1b2e326793bef1ff4cb75153c4088b2e63a92c8ef3dc1e9b16c308b254d90661109ab2faebafa7936309f3e76a5b3f5c36e769a15ee3ff5109602e34c042b9b7047f333dff997a576d6283ca4303a5153250ba8d73e6d8268c3030a463ae0dc1a9f00fa718112aa93b0ddff7e39a345f410b86c2c40615613c91fc715ed4b15b4abf1b81edfa49e184e13752b5821b34f2b5a3c376cbf3f586c764f23eb7d3f0e05ad5e548d19213492f56b668217eb3cee2c01296af2bf6af6e570aa5419eaf62cdde6d12d35c3ad08ddbb7eda77edff11d0499613f67d996de33de7e280f6105524ddc96ec5e9dc01fa243fd44f03db0afed391826175fa82c29ae0c5f415a1eb92af36d1c0f5685da9fbb21d21f1079eb90f9523d8c896d64fbe651c80e2a47c8165f75326d5384ee4aea010fa4dd35c67531262655e2ada738fa2ce92478f5eea97244f9b8381230452bea3f0f0929c721e962c3eebba56229e48d1c7b5a88a37f11312fbc7602603e3a02c56164cdd1678337cd3b6380416bc37cec9b38421911e54cad6d2c55f4d3c6cc0fa83073a5e8235b105ba24392f3a410b04fbe390065886ec960d34f74fd5b2ddf4e60448b150c698fbcaa0653d6f04d61e195fb06527d8d23d73385cacf8649f9eae1b5f6f1102ff3a6eb650d5c02897f51cc340267be3adf77e9a732188e76776bdbae40a7e200671a0e37b7e6169433f810933943b7b400e16a09e710addc42880535ba3a08f2fc5e507b2c38425dbc90f37042f70e9521ccb9ddbca1f4810b665682d6e56e4bdbfb8309005769ceca7131dd70ceba68940fab6c60836eed4859a2795568bb9e9f6e014304614c8cc0820362e7eb5233e18a217087a64a659e9f8f60f31db161fa33d1137c573e741923ad6ce84c247493114f227ffce88ddecab8398ae33278ea0759b4de3f9cb5b14ad338e2c9ee665ae0c3621e6800d52889ea1622c9e15319f124217bff1b375fd5f79cf29030bd6ea1cbcbb7be32d96221cf44f4cbb71413c4e1232ce82d5c0e4f235cd9733995306d88546c9aea4fafec54fad6c7ccf34f80b3aa3ba876345477f093dc7871ecae38f297f705c2d26efbd0a502c39e4cd039e240bca1968204e0ba803cb1a301c929741b7503d569e3503c9ca89ed3a133fa76f74e50adb428eea4085b4f6c8b9fba10360430ab8558cd09b0677f70e32a1e48c064742b4f93087cda427ff2c16545e0e47ebcad40e518ee64fca9d99161df81b2fc74b296f64e24296c463722f50b518f8dbed50a8121073d9e6233887e70adeecdc131d9171a3a792f5c58dd2d2911ad528ee16ba0e3a85fe1d9ecbee095a7d15055c6ad2e148213acd32519e6bfb140337c1a20467674dd1e7b9af5d00db98eded425e9d2c5329a15625ce09118e18861322eae0e03134dc8d6a20c36777b5f217c11bae672e3dac4cd825cf0623591193467117855f1a9090d7e28f115eeb318b9469cd49910b41ab92a16a649466b572c18d7ed1ac4eb6366021af17a7da3c2493149bb676fb5f222662df727da8011e2bde9c17eb0173d9fdb497f56bb4ba4768bf7f1b5236144c72e5ff2bd4ebf0aec7b92361fe3020bd9e0c56051ddb31e1e8b4e9c82361c61e4fcb4d0c7cc91ce08e5174201b03bcd90f358348f82950ce2578d783215628eb22b20e7b4fb5ccd52646b121174b1f23b045424ac601ee546034c81e3dac9b5a98c6e04594776f1433f669ae167c44bc0b0036296c8527f148f05154d20d002dacdd901c3bcd7c4616b559c8ccf0ea9bcfeb911a46c071ceceeab7c8abff77e5b44d0d40e6a3d83e469de84e7545c4cd4023fb812917feea24cad3142d4b6d4fb86dedae4d2f2d9d29244a8f008a493e132b3df54fcbef1f66d5cd07ab9ed5fb55e6bd912711f803fc8ea656fc21092089d20a7a98e78bd49c809b570874226f8479d3e590dd642f9dba18b9ff4ff2d5768a050dc3dc105cba5b754ff5bcab9e35489f9c0f8f9988dd88644abf47f2c17be8488bf4aa5ff7558c72f52c573b830c0003137e64c933d3fc45fa34e83a2deef612eb5eaa1e68f9a8132e98fa501e3685ccd5ee6feb8526ec14d782b634b391d778e6944fe6deda14cd62094a25fdb69d9b0baaa7f63c9b687be7272f4647b8a6631a2c76192854ba052e96e69ee0843e8086c4cfa8cec3ee07effde229de5db05ad02778b4ebb2bfcf3a1dfc3dc72239a5903d3ccec1a407d9c1ffd027debb4cb0f6ba551f902102156b1b21bc3baaa57c4f6aa2e1454e1034ce0e9047018ab6bd8ccbbb80283c1f8c497ee9dc592fb9393b9b83e3676bf09971c873e496a825f90b112f5dfec61f7b6ba2cef9437377d8677a38f0135386defc1b4f9bb9bec4745acfb14ff8eeaf5be220e1180e98776a8e3906b4b5e63feb9a66554c6f55e5b974363febe48577ba6427681099bfafc65e71462a95ce004c8821ea451df703786abd23312df1208d90cb304fb3a3988f2fd11675cf9cca3379b6961909f0770e0d462ac6f9877ef69d7ff6dd9bf7ac024506540d625cacc6a94a53b8dc886644c02ea4df97b08b885312eb8fdde60d12fd69ea9d3dc7b542a7f2249d79e3e82dde18eccdfeb358f0b03c8791862a573d5652a9bc41d28c8a7f79a03b3ae8fe8beae5f9154b47479758bcb0f0f3a1e4e01a88d9204dfa5a782bd20557df15d99e2a0b42def37e82b2d91f00347e7b34b8362ed8fbc78a7727d01ebc939a642acce34f23e025402297fc9d5d9cab6d5cf2c2a85e9fb5926e5520f00ab9db5fb97a608034a89300b420120db326edd1e7c104c805bb0613b7ccb4d200468612b3ed74e157849804b53e1a941f4d657264652e9508cc5fa0481c5785ab714a95fafd34896a98aa409aaae09ba06c931bca346dd97cf42c9840e7969cb06791916b3c13c7484b27218b3941cba09ba41d48a93fffa05265bc12d1c5a5209934579e45874f97826ee179924f254e5c0b5afc6160f741f83f56c96181347603b60b6f5faaa7a1146ecfb9aafce44fdbc64b73af53ef4536f91549d22693d70218d46d873b2b683f1a7791cb562f75eec17e45a7ea2603a3a7e287763612e54d8051c6a41092b4f34ff0ab9b49a9cc4c2bc162254c3ed97a874d13602563ed77bb35d5dcd2a6256c06aef0dbd49b1d5b8b79f522cfd728d25360f406f3b05e896f85baec3379032f09b14c0a985e5df8995e45ce5739d0d4d3d341e72640dc65a9d1aa1e1fc1f09104586bc756b7e5f38cf4d1e0ccd759f81afeff42f35794d0fa0ddb5bcbe2653a7e4c0c8d20f83ea101a4aa9c0ce3c2232f7af0849e917e964d76c6193fc5b2e4b862a8a9d52048b8fe59cab94e938a40d1a8121cb370313b8020c1cdc1e75aaf37e80b41ef15bf73cdec2ec994d71d720f802e29eb60b8dd043806bd378d986b2767acfd60e8b7cac149ce5358f8cd62d077354e98b27cfe9bc99d3b6119de8a5a77375deaa84e8ac72a9725dd47f0beaed4df7232d9f246ef50ec2199cf49a7a02bf4c38eef42efd1b824eb8274bfa33bdb51422f63df95601acae638bec7dc54cfa33127cf5a1692bb5f4072566d01a19e1334661f8f646211ad68f7f91f253709d0b975d52b9a3fba3eaf2db0b0ce656ad64c170d4c1952691b95bcea55fd2569b3163f20d11bf2d006d37b94e52e2a013371706a1cfbd352cde7326eef3241c09cd29f3adce00f3b758bab09ce5fe63b7e7cb8ff1388594fb692a9bf5ff3ebc9407e740a10ba4c1f8dba90983b78af042cd439aeea88312bbdc73de7e7edffecf6043dd8712f24a229fe6b72f15ecf6814311faebcddf9e1c01823def7aea8abdbdf253f26f8ad8f2a287f06f714b015e151c63e44b1f30d00d20388afbd01d72fa5c4a5c81699ff909b55c170036a8cfdbc83e7446b6910ead50691e92063e7def492270e40169c521cd7efa7da107e851e4b710428a3b048d1227668fcdc9eef83d4cc32b46f52807724258a0605102ccd40c4fc4011995ff3514df84a6b6f402c52ae349121d0329a3f42b9687d9b50be1f2a5d2ada271eca83db038b0f2e173fc71aa18ffd5d51c12f0b0333ad4ea7c57d79b445fa35caf3ec382dbade302d53b8f1b2b48b1d8872cef21e6bacd8c0beefebcee4178426bb9ba65d66211e750b6ec2d39bb609cd7ff4cb738f763e00257c02a3f5f00bab355da284b6df7622fdad7fe120a57a6a59242cf0604182ce1c47eb86eb89b2264a8f24d7419f55eb039eacd0f0cd3e712b4da767b51d0b905372ce3816ad7e7817e0123ba4e0971f3f98d5516b83e69e15bc2dd8eee2a4abe4611047186933087b83a87da6a061c643741b16881f14f5e00b9ac6230b9d051ca990730beb7a0fb3b8f964c4ec92d4da9f2fedf4f022e73ab1764cd50ad0804e0ee9ce331df5a9ec6f26b09f4ea8daa4f91107ed41e6a3e082604efc417f8c2cfeda4d8bbf77e33db8c28f6bc4fed06127efc8938eacc03d4076e9809c2fbd26ed51d57efd226c1edc85ff249ad35ee9c8c8085fc9c4b8a7bd8b7c521824af4e8d2132b3bc075bfe6aa99a2da1025ee138b722d0e9f8d5cbaea8d6d631c6a95b83fd0613ba18772d4fa2b47bed1b6239b301cd8836dd946b26ae74508551c7ee8b62ba7d5e953c66898bcc7d1ba19218136749a00ec12a66a8b6d746f259390fb494e998db4363718e1bd25fc5a2978d98184d4c18db7a0f1dc80a42e6c403fc0bcd7b70dd2de18791e908c159fd85b35ece97b2356b17c511f7e0e1874d74acf4dd8aaa2e2864ffed4674a30e00533b67b18347bfc2a4d3bddece4b640b3c8ca21514152a08a20b6d718255b6c1d9507f6c16649f1e781c9d48428b1040919addc5ed53c7056f4fd8ca63c60baebf43822ae95fe53bfca4d9d2b22256b44c13892145b91b25646de7f9cbece60e930aeb4c58a5d164dd4302ee6b3eb3599fca1ded77a6ccbbba8e13db076d360141d707ba28c5fb07c882d59378d2c53b0d571932ee9a6d423cd93ee95669bf943aa74a35f3c7e6a229f18a94d2f90c90a4b2fd8d7d0709730d1aabe86db421d8cc855666f60e1f2678b2921903ddedc0841d3768adb3821e85027393346a361941fcc2857a1a99dd2910adcc566f86151c9c1df5944d99a7f3e4096ce38971be79b027e62e4570e42829d17254115efa4dcbd2b21334e65a8a6d3893476a7a86def4eb544cd8249eb401cedfef0d747d76bb92defed42c597a06025284632f20674802a1c913d682da386c9c4d862f81072dd4903590375eb34ddcafd6c667b285da381f77219ff343a7316b96b53f165996e98d1e687a7baa3b29b372c0191fe6e2b5d77a142f8f3b420c2a375020274d8a037c6be1bbe1a3b9d8e6107fdd0ae37f6401b03fa35daae0a9cad88b20dbe3d953418f74ef67a921d26423e493d69917092e32368e61904bcabaa7a55938091a6885c07e4f57d4cadd0872df9d5c7a419e37e00385444665986e1310df1a8737ac6eca2484494bcccec2a2711968010ab1372ba29e780ceeded7d55beed77aa7fd983e7cd7b5cc97da79f5dad784b6299e7cecf31004b341147ea26e5cd35baf7ca5c8f95f5737c7e114243738d1f855f55b7def67914490b7d6ae4976cd4f24070638b4fe0cb198b8d420de7edfcf6686f5d6af8438119977b71b17157675d442af449b2536301271fbf6879e332c646603494c686b22df5e529e84f96cc8f6273efcc92fabb4987ffe72c489f20fe38cc9a87119f21e80f72ed6301462532a3d275d5b9c198af168c28f3d9ba49b2747618f00ccc26a2ce256f749c980d4739a762cd396392f3f821a0fe166b65a0cfd748454393a53013a39f5c9613569da0cc69ac78a234e7268997020b693057e011b4122e10d7a4d58e1fbb739840e82a14bbf73bab9875aaeacc6f65c651cb8a20ce6b41681d772aa7488862be5ea8990b41d30b24a2b88eb32f42d3871c46bce9fa762af9b2b1bbffae34d6eb015c10ed4be6f3d16d6a6f9d42414f57a973e8a9c7163dc4c0763471b1fff6c6ecb8ae3af0b3fb976b820a0147c467d5430a9caca674ddcf23b72bbb41149e3dd3054853ad58682bc6f1e6d170b014490d3592ecda12aa3f7e127899ed58861405f4b74282f167d594d633e451a6473f82a22cbe49f3d6588d6cfdaab0139c760dfdde6eedd6e65";
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
            // $res['response'] = "d1b28012646941dd58185ec704a94818f1f123087515238003ee252a8aebd06b5d6b6dfb06cc2f2a02ee374a23a1764c1a869dcca03baad59e178d68114572cf9df93f9c9e1fee3cf78f1a0d64c3e959cf7b1e6cde41c88cf055de876d13fb5a7580f95f77df36b84ee1992633270289ef65c290a7444f9e7e2e5098f6eeae2e29071af06120fca36cd18b5b2d79933a270214bd4f174fa56a1cae3ff785d65f44aedf019041c171c6e885ebf577a35f22d0d4e636dab55c34743156798924238c83447859deb2e3b7eb9539f727a2faef2f4f7a5fa6399f35bb1e6c64a39683be2beeb2d6e70b86c6c3a1264f7b8ef7d1a91865323a6d641148ada09683d1eadedb45b46b0ae8490932176c3bbcd6afd9d502a26d98214768323d0b3a283f7cd891cc6a400122458127bcea470f0816c7a9e4de1deb94496c32342ae0331515443795b83bdbd7e2a3b5bcfef9aba3c9c91fddcecc9d3a9b56cd2c49d44007b7540b847ae32636e5a257f174ef4d4982253acab0c5b152625f18a20f8823f4b540076858f79bf31cd858d4eb0048e54ccb74e92c63ce0f9511f319ec87515512a3560a7b8ae991ad2fe08f1b38772f33edaaa178ac17f24960a96eaa4df36742f5fd1407ea3d715290d588e7f5f5dd372fd093027ed6e4221a83b4c0052214dc";
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


    public function billPayment(Request $request){
        try {
            // dd($request)->all();
            $user = Auth::user();
            $data = $request->all();

            // Extract main fields from JSON
            $inputs         = $data['inputParams']['input'] ?? [];
            $additionalInfo = $data['additionalInfo']['info'] ?? [];
            $params         = $data['params'] ?? [];
            $billAmount     = $data['amount'] ?? '0.00';
            $billerId       = $data['billerId'] ?? '';
            $requestId      = $data['requestId'] ?? '';
            $billDate = $data['billerResponse']['billDate'] ?? '';
            $dueDate = $data['billerResponse']['dueDate'] ?? '';
            $billNumber = $data['billerResponse']['billNumber'] ?? '';
            $billPeriod = $data['billerResponse']['billPeriod'] ?? '';
            $customerName = $data['billerResponse']['customerName'] ?? '';

            // Normalize inputs
            if (isset($inputs['paramName'])) {
                $inputs = [$inputs];
            }
            $inputXml = '';
            foreach ($inputs as $input) {
                $paramName  = $input['paramName'] ?? '';
                $paramValue = $input['paramValue'] ?? '';
                $inputXml  .= '<input><paramName>' . htmlspecialchars($paramName) . '</paramName><paramValue>' . htmlspecialchars($paramValue) . '</paramValue></input>';
            }

            // Normalize additionalInfo
            if (isset($additionalInfo['infoName'])) {
                $additionalInfo = [$additionalInfo];
            }
            $infoXml = '';
            foreach ($additionalInfo as $info) {
                $infoName  = $info['infoName'] ?? '';
                $infoValue = $info['infoValue'] ?? '';
                $infoXml  .= '<info><infoName>' . htmlspecialchars($infoName) . '</infoName><infoValue>' . htmlspecialchars($infoValue) . '</infoValue></info>';
            }

            // Params (Vehicle Number etc.)
            $paramsXml = '';
            foreach ($params as $key => $value) {
                $paramsXml .= '<option><amountName>' . htmlspecialchars($key) . '</amountName><amountValue>' . htmlspecialchars($value) . '</amountValue></option>';
            }

            // Generate XML in single line
            $xml = $xml = '<?xml version="1.0" encoding="UTF-8"?><billPaymentRequest><agentId>CC01RP16AGTU00000001</agentId><billerAdhoc>true</billerAdhoc><agentDeviceInfo><ip>103.250.165.8</ip><initChannel>AGT</initChannel><mac>01-23-45-67-89-ab</mac></agentDeviceInfo><customerInfo><customerMobile>9220480604</customerMobile><customerEmail></customerEmail><customerAdhaar></customerAdhaar><customerPan></customerPan></customerInfo><billerId>' . htmlspecialchars($billerId) . '</billerId><inputParams>' . $inputXml . '</inputParams><billerResponse><billAmount>' . htmlspecialchars($billAmount) . '</billAmount><billDate>' . $billDate . '</billDate><billNumber>' . $billNumber . '</billNumber><billPeriod>' . $billPeriod . '</billPeriod><customerName>' . $customerName . '</customerName><dueDate>' . $dueDate . '</dueDate><amountOptions>' . $paramsXml . '</amountOptions></billerResponse><additionalInfo>' . $infoXml . '</additionalInfo><amountInfo><amount>' . htmlspecialchars($billAmount) . '</amount><currency>356</currency><custConvFee>0</custConvFee><amountTags></amountTags></amountInfo><paymentMethod><paymentMode>Cash</paymentMode><quickPay>N</quickPay><splitPay>N</splitPay></paymentMethod><paymentInfo><info><infoName>Remarks</infoName><infoValue>2</infoValue></info></paymentInfo></billPaymentRequest>';

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
                'message' => "Something went wrong in payment"
            ]);
        }
    }
}

