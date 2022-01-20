<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class NotifiationRevisiones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:NotificationRevisiones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Job para notificar Revisiones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line("Some text");
        $today    = date("Y-m-d");
        $tomorrow = date("Y-m-d",strtotime($today."+ 1 days"));

        $this->line("HOY");

        $data_today    = DB::table("appointments_agenda")->where("fecha", $today)
                            ->join("revision_appointment", "revision_appointment.id_revision", "=", "appointments_agenda.id_revision")
                            ->join("clientes", "clientes.id_cliente", "=", "revision_appointment.id_paciente")
                            ->get();
        $data_tomorrow = DB::table("appointments_agenda")->where("fecha", $tomorrow)
                            ->join("revision_appointment", "revision_appointment.id_revision", "=", "appointments_agenda.id_revision")
                            ->join("clientes", "clientes.id_cliente", "=", "revision_appointment.id_paciente")
                            ->get();

        foreach($data_today as $value){
            if($value->fcmToken){
                $this->line($value->fcmToken);
                switch ($value->id_line) {
                    case 17:
                       $apiKey = "AAAAg-p1HsU:APA91bHJHYE__7tBgvxXHPbMwR2cm7-KyYOknyMz7fAfBYm34YrFMF9QK4FieAEPL54o7EPXilihGevzxoBSf3X4CCHAswTk9NctvFTYY1ftYTYI5hj_-qXVFtCizHHzM060Ojphq62q";
                        break;

                    case 6:
                        $apiKey = 'AAAAYEG4vHw:APA91bGFWTsV1GsGaNdTZ7zNh4lM7zzTVO6cH-uB6bbjLxoUo3gfYDoIEMtbU6ioIC1BmAKVI3D_btMJg2Jd3urI8l3Pb9noB0FEkU7_6EGKnv7ymZULMwv0dKLCEprIuKwMJNUQDrEe';
                        break;
                    case 7:
                        $apiKey = 'AAAAYEG4vHw:APA91bGFWTsV1GsGaNdTZ7zNh4lM7zzTVO6cH-uB6bbjLxoUo3gfYDoIEMtbU6ioIC1BmAKVI3D_btMJg2Jd3urI8l3Pb9noB0FEkU7_6EGKnv7ymZULMwv0dKLCEprIuKwMJNUQDrEe';
                        break;
                    case 8:
                        $apiKey = 'AAAAYEG4vHw:APA91bGFWTsV1GsGaNdTZ7zNh4lM7zzTVO6cH-uB6bbjLxoUo3gfYDoIEMtbU6ioIC1BmAKVI3D_btMJg2Jd3urI8l3Pb9noB0FEkU7_6EGKnv7ymZULMwv0dKLCEprIuKwMJNUQDrEe';
                        break;

                    case 9:
                            $apiKey = 'AAAACnbvohQ:APA91bFxy5YUQlAXGxEF2BjU-bLXp1C13ClaB3kPvAFCwqraIyNRXwXYueJRxNeAGmSfaKCD2SgFyd03HfxgDODR74k630dC3q-Gy56U_EuNea6Fr2ZXeTs_mQ3nK--LqXdQ37zm7dt0';
                            break;

                    case 21:
                        $apiKey = 'AAAACnbvohQ:APA91bFxy5YUQlAXGxEF2BjU-bLXp1C13ClaB3kPvAFCwqraIyNRXwXYueJRxNeAGmSfaKCD2SgFyd03HfxgDODR74k630dC3q-Gy56U_EuNea6Fr2ZXeTs_mQ3nK--LqXdQ37zm7dt0';
                        break;

                    case 16:
                        $apiKey = 'AAAA5qKc4M8:APA91bG3q9BAo323Bje7_eIs8sGa-G37pRr67n4IgYK8xnoYHsSOx397JPecFVVaWCHfHjcqiaTFOjaZPbEtzXbzakZ2kwWqP_2x9RT3_Z883-lKpbRRgALZSq5MXK51Cb-W6Db5xAQu';
                        break;
                    case 3:
                        $apiKey = 'AAAADwEmOL8:APA91bGN_99QtWkpjpvLTjByVISmGtM7qZSgvZNixW1o7d1udxNp_hHI8n6Tn-ukuGgmnLAIABuWYo-Kdea253E-jE1tCVBLQmRikVBbdxy2Th-j64BAr80U9FeCpr3gGmPW66W58ZYF';
                            break;

                    case 2:
                        $apiKey = 'AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx';
                            break;

                    case 18:
                        $apiKey = 'AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx';
                            break;

                    case 22:
                        $apiKey = 'AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx';
                            break;



                    default:
                        $apiKey = 'AAAA3cdYfsY:APA91bF1mZUGbz72Z-qZhvT4ZFTwj6IUxAIZn9cchDvBxtmj47oRX6JKK8u8-thLD94GBUiRRGJqVndybDASTjHLwiRTkQlqyYqyCf4Oqt3nTqdeyh246t5KSXcPWUvY9fSp1bbOrg_L';
                        break;
                }

                $FCM_token = $value->fcmToken;
                $url = "https://fcm.googleapis.com/fcm/send";
                $token = $FCM_token;
                $serverKey = $apiKey;
                $title = "Recordatorio Cita de Revisión";
                $body = "Tienes una cita de Revision para el dia de Hoy: ".$value->fecha;
                $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
                $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
                $json = json_encode($arrayToSend);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: key='. $serverKey;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                //Send the request
                $response = curl_exec($ch);
                //Close request
                if ($response === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
            }
        }


        $this->line("MAÑANA");
        foreach($data_tomorrow as $value){
            if($value->fcmToken){
                $this->line($value->fcmToken);
                switch ($value->id_line) {
                    case 17:
                        $apiKey = "AAAAg-p1HsU:APA91bHJHYE__7tBgvxXHPbMwR2cm7-KyYOknyMz7fAfBYm34YrFMF9QK4FieAEPL54o7EPXilihGevzxoBSf3X4CCHAswTk9NctvFTYY1ftYTYI5hj_-qXVFtCizHHzM060Ojphq62q";
                         break;

                     case 6:
                         $apiKey = 'AAAAYEG4vHw:APA91bGFWTsV1GsGaNdTZ7zNh4lM7zzTVO6cH-uB6bbjLxoUo3gfYDoIEMtbU6ioIC1BmAKVI3D_btMJg2Jd3urI8l3Pb9noB0FEkU7_6EGKnv7ymZULMwv0dKLCEprIuKwMJNUQDrEe';
                         break;
                     case 7:
                         $apiKey = 'AAAAYEG4vHw:APA91bGFWTsV1GsGaNdTZ7zNh4lM7zzTVO6cH-uB6bbjLxoUo3gfYDoIEMtbU6ioIC1BmAKVI3D_btMJg2Jd3urI8l3Pb9noB0FEkU7_6EGKnv7ymZULMwv0dKLCEprIuKwMJNUQDrEe';
                         break;
                     case 8:
                         $apiKey = 'AAAAYEG4vHw:APA91bGFWTsV1GsGaNdTZ7zNh4lM7zzTVO6cH-uB6bbjLxoUo3gfYDoIEMtbU6ioIC1BmAKVI3D_btMJg2Jd3urI8l3Pb9noB0FEkU7_6EGKnv7ymZULMwv0dKLCEprIuKwMJNUQDrEe';
                         break;

                     case 9:
                             $apiKey = 'AAAACnbvohQ:APA91bFxy5YUQlAXGxEF2BjU-bLXp1C13ClaB3kPvAFCwqraIyNRXwXYueJRxNeAGmSfaKCD2SgFyd03HfxgDODR74k630dC3q-Gy56U_EuNea6Fr2ZXeTs_mQ3nK--LqXdQ37zm7dt0';
                             break;

                     case 21:
                         $apiKey = 'AAAACnbvohQ:APA91bFxy5YUQlAXGxEF2BjU-bLXp1C13ClaB3kPvAFCwqraIyNRXwXYueJRxNeAGmSfaKCD2SgFyd03HfxgDODR74k630dC3q-Gy56U_EuNea6Fr2ZXeTs_mQ3nK--LqXdQ37zm7dt0';
                         break;

                     case 16:
                         $apiKey = 'AAAA5qKc4M8:APA91bG3q9BAo323Bje7_eIs8sGa-G37pRr67n4IgYK8xnoYHsSOx397JPecFVVaWCHfHjcqiaTFOjaZPbEtzXbzakZ2kwWqP_2x9RT3_Z883-lKpbRRgALZSq5MXK51Cb-W6Db5xAQu';
                         break;
                     case 3:
                         $apiKey = 'AAAADwEmOL8:APA91bGN_99QtWkpjpvLTjByVISmGtM7qZSgvZNixW1o7d1udxNp_hHI8n6Tn-ukuGgmnLAIABuWYo-Kdea253E-jE1tCVBLQmRikVBbdxy2Th-j64BAr80U9FeCpr3gGmPW66W58ZYF';
                             break;

                     case 2:
                         $apiKey = 'AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx';
                             break;

                     case 18:
                         $apiKey = 'AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx';
                             break;

                     case 22:
                         $apiKey = 'AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx';
                             break;
                    default:
                        $apiKey = 'AAAA3cdYfsY:APA91bF1mZUGbz72Z-qZhvT4ZFTwj6IUxAIZn9cchDvBxtmj47oRX6JKK8u8-thLD94GBUiRRGJqVndybDASTjHLwiRTkQlqyYqyCf4Oqt3nTqdeyh246t5KSXcPWUvY9fSp1bbOrg_L';
                        break;
                }

                $FCM_token = $value->fcmToken;
                $url = "https://fcm.googleapis.com/fcm/send";
                $token = $FCM_token;
                $serverKey = $apiKey;
                $title = "Recordatorio Cita de Revisión";
                $body = "Tienes una cita de Revision para el dia de Mañana: ".$value->fecha;
                $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
                $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
                $json = json_encode($arrayToSend);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: key='. $serverKey;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                //Send the request
                $response = curl_exec($ch);
                //Close request
                if ($response === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);

            }
        }
    }
}
