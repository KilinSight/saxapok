<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    const BOT_API_SET_WEBHOOK = 'setWebhook';
    const BOT_API_GET_UPDATES = 'getUpdates';
    const BOT_API_SEND_MESSAGE = 'sendMessage';

    /**
     * @Route("/get_csv", name="get_csv")
     * @param Request $request
     * @return mixed
     */
    public function getRunDataAction(Request $request)
    {

        $params = http_build_query(array(
            "api_key" => ApiController::apikey,
            "format" => "csv"
        ));

        $result = file_get_contents(
            'https://www.parsehub.com/api/v2/runs/' . ApiController::PARSEHUB_RUN_TOKEN . '/data?' . $params,
            false,
            stream_context_create(array(
                'http' => array(
                    'method' => 'GET'
                )
            ))
        );
        file_put_contents('C:/csv/test.csv', $result);

        return new Response($result);
    }

    /**
     * @Route("/bot/make_request/{method}", name="make_request", options = {"expose" : true})
     *
     * @param Request $request
     * @param string $method
     *
     * @return mixed
     */
    public function makeRequestAction(Request $request, $method)
    {

        $allowedMethods = [
            self::BOT_API_SET_WEBHOOK,
            self::BOT_API_SEND_MESSAGE,
            self::BOT_API_GET_UPDATES,
        ];

        if (!in_array($method, $allowedMethods)) {
            return new JsonResponse('Method not allowed.');
        }
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_PROXY, $proxy);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        if ($method === self::BOT_API_SET_WEBHOOK) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, ['url' => ApiController::saxapokWebhookUrl]);
        } elseif ($method === self::BOT_API_GET_UPDATES) {
//            curl_setopt($curl, CURLOPT_POSTFIELDS, ['url' => ApiController::saxapokWebhookUrl]);
        } elseif ($method === self::BOT_API_SEND_MESSAGE) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, ['message' => 'test']);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($curl);
        if ($output === false) {
            throw new \Exception(curl_error($curl), curl_errno($curl));
        }
        print_r($method);
        var_dump($output);
        die;

        curl_close($curl);

        return new JsonResponse($output);
    }

    /**
     * @Route("/saxapok_webhook", name="saxapok_webhook")
     * @param Request $request
     * @return mixed
     */
    public function webhookAction(Request $request)
    {
        ApiController::apikey;
        ApiController::botapikey;
        ApiController::PARSEHUB_RUN_TOKEN;
        ApiController::saxapokWebhookUrl;

        return 'Hello bruh!';
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {


        return $this->render('saxapok/index.html.twig');
    }

    /**
     * @Route("/parse", name="parse")
     * @param Request $request
     * @return mixed
     */
    public function parseImagesAction(Request $request)
    {
        $params = array(
            "api_key" => self::apikey,
            "start_url" => "http://pinterest.ru",
            "start_template" => "login",
            "start_value_override" => "",
            "send_email" => "1"
        );

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
                'content' => http_build_query($params)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents('https://www.parsehub.com/api/v2/projects/tRRO22Ex294T/run', false, $context);
        print_r($result);

        return new Response();
    }

    /**
     * @Route("/get_results", name="search_get_results", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     */
    public function getResultsAction(Request $request)
    {
        $root = 'O:' . DIRECTORY_SEPARATOR . 'data';
        $filesList = scandir($root . DIRECTORY_SEPARATOR . 'csv/');
        foreach ($filesList as $file) {
            $imagesUrls[] = fgetcsv($file);
        }

        print_r($imagesUrls);


        return new Response();
    }
}
