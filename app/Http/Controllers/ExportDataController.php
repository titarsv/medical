<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExportDataController extends Controller
{
    /**
     * Данные для битрикс
     */
    private $crm_host = 'parfumhous-ua.bitrix24.ua';
    private $crm_port = '443';
    private $crm_path = '/crm/configs/import/lead.php';
    private $crm_login = 'manager@parfumhouse.com.ua';
    private $crm_password = 'manager681';
    /*
     *
     */

    /**
     * @param $data
     * @return string
     */
    public function toBitrix($data)
    {
        $crm_host = $this->crm_host;
        $crm_port = $this->crm_port;
        $crm_path = $this->crm_path;
        $crm_login = $this->crm_login;
        $crm_password = $this->crm_password;

        $postData = array(
            'TITLE' => date('d.m.Y H:i:s'),
            'PHONE_MOBILE' => $data['phone'],
            'COMPANY_TITLE' => $data['content'] ? $data['content'] : '',//зачем msg?
            'NAME' => $data['name'] ? $data['name'] : '',
            'EMAIL_WORK' => $data['mail_skype'] ? $data['mail_skype'] : '',
            'COMMENTS' => 'Сайт магазин. О закаве: <br>'.$data['products'],
            'LOGIN' => $crm_login,
            'PASSWORD' => $crm_password,
            'UF_CRM_1474615724' => 'Сайт Магазин'
        );

        $fp = fsockopen("ssl://".$crm_host, $crm_port, $errno, $errstr, 30);
        if ($fp)
        {
            $strPostData = '';
            foreach ($postData as $key => $value) {
                $strPostData .= ($strPostData == '' ? '' : '&') . $key . '=' . urlencode($value);
            }
            // prepare POST headers
            $str = "POST ".$crm_path." HTTP/1.0\r\n";
            $str .= "Host: ".$crm_host."\r\n";
            $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $str .= "Content-Length: ".strlen($strPostData)."\r\n";
            $str .= "Connection: close\r\n\r\n";
            $str .= $strPostData;

            fwrite($fp, $str);
            $result = '';
            while (!feof($fp))
            {
                $result .= fgets($fp, 128);
            }
//            $result = fread($fp, 128);
            fclose($fp);
            $result = 'Connection Established!';
        }
        else
        {
            $result = 'Connection Failed! '.$errstr.' ('.$errno.')';
        }
        $this->logBitrixRequest($result);

        return;
    }

    public function logBitrixRequest($request)
    {
        $category = base_path();
        $fp = fopen($category.'/public/logs/bitrix_log.txt', "a");
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $curent_time = date("H:i:s d.m.Y", time());
        $result = fwrite($fp, $user_ip." in $curent_time Result: $request \r\n");
        fclose($fp);
        return $result;
    }
}
