<?php
if ( ! function_exists('httpRequest')) {

    /**
     *
     * 发送curl请求
     *
     */
    function httpRequest( $url , $params = [] ,$method = 'get')
    {
        $ch = curl_init();
        $method = strtolower($method);
        if ($method == 'get') {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            if ( !empty( $params )) {
                $queryString = http_build_query($params);
                $url .= "?" . $queryString;
            }
        } else if ($method == 'post') {
            !empty($params) && $params = http_build_query($params);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=UTF-8"));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $tResult = curl_exec($ch);
        curl_close($ch);
        return $tResult;
    }
}
