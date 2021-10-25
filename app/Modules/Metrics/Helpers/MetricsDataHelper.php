<?php

namespace Modules\Metrics\Helpers;


use GuzzleHttp\Client;
use Prometheus\RenderTextFormat;

class MetricsDataHelper
{
    /**
     * @param string $metrics_name - name metrics(example: product_data)
     * @param float $result - count result data (example 5.5)
     * @return string
     */
    public static function convertToMetrics(string $metrics_name, float $result): string
    {
        return "$metrics_name $result";
    }

    public static function convertToMetricsWithParams(string $metrics_name, float $result, array $params): string
    {
        $str_params = '';
        foreach ($params as $key => $value) {
            $str_params .= "$key=\"$value\"";
            if (array_key_last($params) !== $key) {
                $str_params .= ',';
            }
        }

        return "$metrics_name{{$str_params}} $result" . PHP_EOL;
    }

    /**
     * @param string $name_metrics
     * @param array $data example: [['value' => 5.5, 'params' => ['code' => 'DSD']]]
     * @return string
     */
    public static function convertMultiDataToMetricsWithParams(string $name_metrics, array $data): string
    {
        $str_result = '';
        foreach ($data as $metrics_data) {
            $str_result .= self::convertToMetricsWithParams($name_metrics, $metrics_data['value'], $metrics_data['params']);
        }
        return $str_result;
    }

    public static function pushMetrics(string $job_name, string $str_result, string $instance = 'host.docker.internal:80'): bool
    {
        $client = new Client(['verify' => false, 'timeout' => 10]);
        $requestOptions = [
            'headers' => [
                'Content-Type' => RenderTextFormat::MIME_TYPE,
            ],
            'connect_timeout' => 10,
            'timeout' => 20,
        ];
        $url = "http://165.22.39.66:9091/metrics/job/{$job_name}/instance/$instance";
        /*$url = "http://host.docker.internal:9091/metrics/job/{$job_name}/instance/$instance";*/
        $request_options['body'] = "$str_result\n";

        if ($response = $client->request('POST', $url, $request_options)) {
            return true;
        }
        return false;
    }
}