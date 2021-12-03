<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
use App\SocietyUsers;
use App\UserLogin;


class SendNotification extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'send:notification';
   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Command description';
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

   public function fcmNotification($device_id, $sendData)
   {
        #API access key from Google API's Console
    if (!defined('API_ACCESS_KEY')){

        // define('API_ACCESS_KEY', 'AAAATmZU4nA:APA91bGClTtsQEYtexrS3tdYGTca7Q2UhwWGHplyx7vjXoE2RgMihRt1oc2z-SepjOIDXDVkGmps4X1jKa-YPzUpyYKe6RUWl-isZ2_S8o_Npyh18FFltQKIgeFEQexhKQl07gHQTdEm');


        define('API_ACCESS_KEY', 'AAAA-ub9LE8:APA91bFxQB0OiVLwiAhK0YtrnVdAObaX5HG8nRxe-n88lrgK0Cqn-6cxmr9xsrfcSmW2beyq8mtyrbOqPzWEYGmhqFYC7ggl4e1ec-AeKE66MRFBvKvR0HGqY6ftSXRID89LOuBb64yd');

    }

    $fields = array(
        'to'    => $device_id,
        'data'  => $sendData,
        'notification'  => $sendData
    );


    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );
        #Send Reponse To FireBase Server    
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, true );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch);
        //$data = json_decode($result);
    if($result === false)
        die('Curl failed ' . curl_error());

    curl_close($ch);

    //prd($result);
    return json_decode($result);
}








public function handle()
{
       //
        //$tsuccess = DB::table('new')->insert(array('name'=>'dfasfsfsfsdfsdfds'));

    $all_notifications = DB::table('send_notification_type')->where('status','N')->get();
    if(!empty($all_notifications)){
        foreach($all_notifications as $notif){

            if($notif->type == 'society'){
                $users = SocietyUsers::where('society_id',$notif->society_id)->get();
                if(!empty($users)){
                    foreach($users as $user){
                        $user_logins = UserLogin::where('user_id',$user->id)->get();
                        if(!empty($user_logins)){
                            foreach($user_logins as $login){

                               $deviceToken = $login->deviceToken;
                               $sendData = array(
                                'body' => $notif->text,
                                'title' => $notif->title,
                                'image'=>$notif->image,
                                'sound' => 'Default',
                            );
                               $result = $this->fcmNotification($deviceToken,$sendData);

                               if($result){
                                $dbArr = [];
                                $dbArr['user_id'] = $login->user_id;
                                $dbArr['text'] = $notif->text;
                                $dbArr['title'] = $notif->title;
                                $dbArr['image'] = $notif->image;
                                $dbArr['is_send'] = 1;

                                DB::table('notifications')->insert($dbArr);
                            }
                        }
                    }
                }
            }

            DB::table('send_notification_type')->where('id',$notif->id)->update(['status'=>'Y']);


        }else if($notif->type == 'block'){

        }else{

        }




    }
}




}
}