<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AmoController extends Controller
{
    private $client_id = '3c8f1b95-f748-4b97-be94-91838a8db9d7';
    private $client_secret = 'mC4Svc7dc9zvUijrMchW5yL1GlEGsGkNDA0OAUvbQIuRnc413F7cF99ePEGT8VGu';
    private $grant_type = 'authorization_code';
    private $code  = 'def50200eb61bb510fb1173dd4fa3d4f107f9a279b114fc720e4591e6a4ae2c360d4838657b91e12bc448492e7ee637e67d74dc08a8daba025fdc246d2fe2305f3a7422a4da204f2e199812c8e144f3d43a64efab40f54a55675fed9cf42de85729355b4b74252d2d10047562bfb992aeb53f40c84a9d3530b83cc081c6f2422b8fd7751549a3c6743c3607fd69b7547b8be8759d9752a7d846d1ec1a7e1d1ded7644e7bdac2b8cddf8c42d8fdba38662b81bd6b84b64396dc00ea5e8e2a66a32c5b27d720807ec3c799e3bfc9eea0f897c780457f9acafdc5dbf75da40100d99c57d01068c17d4dce463bc341819b5c0b2b15846d2382ec56538d097ed03529ec85111e19c1afef1a06182e2fd4e70bc6d1d988e6ee0fba934dd465f8221dcc535190b80fea207e8235dd308c7d201e37f170dc09898749d64b725481cc8292a3c033c6750cc852d0698a0c3cacd04e62bb2b76bf6084308204e1fd1bdd3696979e5145b25c7dbae49ba26f7fe748628ae4f2a631c96b83012d6c2ba93d5d81a951afe1cedf74f41298ac7e7ff0f45d462fc52eff19a869c0678c7360f0cd5ac2413b24a160b48a56024c992e8c165eaf2ac315c6d4bd015728c0386364c5856226c1964fbaa6ef95613e0f31489bc4';
    private $redirect_uri = 'http://natali.milota.online/amo-token';
    private $url = 'https://www.amocrm.ru//oauth2/access_token';
    private $refresh_token = 'def502000dd86a5ff462dab4d75f0c51c1fc1d7aa98b4bdcf900dc2848490f7de0df7a2f69fd6037b69d933c1bfd40cf841c8cc57f7dd87f675146500791bb39989c474fcc3e1922f77bd92cbd774352fa669e5f779cad9df82b299395a3e2ff78fa71a2f1ba2785feb99b2e3573becf7fa5008c302e4fbb265fa0af5e417a1d499e89dc560b978eab792388eb81ccf8e7e69aee540fa48c4b106303e678b451fa212774f607ff9fa3c8a72c52674f1ae322aae63e83cf4ef62b26d8474cd0edf4a25bebe4557ba847dd4cc6efeef1e5bbc57b5e76dcf4a8cfb505c52a6bc457431053c99c4980fccf1d79608cb5e145a888e2caad06e4dbebac6554d1651f472c8495ce3a97a957b2190426349213e4ef81b1f643c05663d291b1d230827f101ed375cfcfea787a82056507a851ebeb6bbc7b8fdb92be4a9d0c6bc6aa3e3a4424944729683086f68126b9003869918603641e293653dc9c17ff86b30142a020037998b8880aef0981ecd8ad44c6f00557644a4f90895e68ec7eb2ce2f3b21f4d954839bf4e75164298d204ff0bd67f227ccd0d6cca3e459950ad2f8c21220a0ad8cb29cfc627abaa27fa4186c4a7f479660544628a34050c873fc0b7d91a050652292561f6dd42439f0';
    private $access_token = '';

    public function __construct()
    {
        $params = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refresh_token,
            'redirect_uri' => $this->redirect_uri
        ];
        $token = $this->send_request_post($params);

        $this->access_token = $token['access_token'];
    }

    public function auth()
    {
        dd($this->access_token );
    }



    private function send_request_post($params)
    {
        $subdomain = 'yermishovyandexua';
        $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($out, true);
        return $response;
    }
    public function addProduct()
    {
        $subdomain = 'yermishovyandexua';
        $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/catalog_elements';
        $authorization = [
            'Authorization: Bearer ' . $this->access_token
        ];
        $products = Product::all();
        foreach ($products as $product)
        {
            $catalog_elements [ 'add' ] = array (
                array (
                    'catalog_id' => 14295 ,
                    'name' => $product->name,
                    'custom_fields'=> [
                        [
                            'id' => 1085901,
                            'values'=>[
                                'value'=>$product->sku
                            ],
                        ],
                        [
                            'id' => 1085905,
                            'values'=>[
                                'value'=>$product->price
                            ],
                        ],
                        [
                            'id' => 1085903,
                            'values'=>[
                                'value'=>$product->quantity
                            ],
                        ],
                        [
                            'id' => 1085907,
                            'values'=>[
                                'value'=>'м3 (кубические метры)'
                            ],
                        ],
                        [
                            'id' => 1085909,
                            'values'=>[
                                'value'=>$product->old_price
                            ],
                        ],


                    ]
                )
            ) ;
            dd($catalog_elements);
            $paramentr = $this->send_request_post_1($link,$catalog_elements, $authorization);
            $product->update(['amo_id'=>$paramentr['_embedded']['items'][0]['id']]);
            echo($product->name);
        }

    }
    public function updateProduct($id)
    {
        $subdomain = 'yermishovyandexua';
        $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/catalog_elements';
        $authorization = [
            'Authorization: Bearer ' . $this->access_token
        ];
        $product = Product::find($id);
        $catalog_elements [ 'update' ] = array (
            array (
                'catalog_id' => 14295 ,
                'id' => $product->amo_id ,
                'name' => $product->name,
                'custom_fields'=> [
                    [
                        'id' => 1085901,
                        'values'=>[
                            'value'=>$product->sku
                        ],
                    ],
                    [
                        'id' => 1085905,
                        'values'=>[
                            'value'=>(float)$product->price
                        ],
                    ],
                    [
                        'id' => 1085903,
                        'values'=>array('value'=>$product->quantity)
                    ],
                    [
                        'id' => 1085907,
                        'values'=>[
                            'value'=>'м3'
                        ],
                    ],
                    [
                        'id' => 1085909,
                        'values'=>[
                            'value'=>(float)$product->old_price
                        ],
                    ],


                ]
            )
        ) ;
        $paramentr = $this->send_request_post_1($link,$catalog_elements, $authorization);
        $product->update(['amo_id'=>$paramentr['_embedded']['items'][0]['id']]);

    }
    public function status()
    {
        $subdomain = 'yermishovyandexua';
        $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/catalog_elements?catalog_id=14295';
        $authorization = [
            'Authorization: Bearer ' . $this->access_token
        ];
        $paramentr = $this->send_request_get($link, $authorization);

        dd($paramentr);
    }

    private function send_request_post_1($url, $params, $authorization)
    {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$authorization);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response = json_decode($out, true);
        return $response;
    }
    private function send_request_get($url, $authorization)
    {
        $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $authorization);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return json_decode($out);
    }
}
