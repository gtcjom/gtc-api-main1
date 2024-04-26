<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route;

use App\Validator;  
use App\ModelHelper;
use App\ModelSync;
use App\ModelEncoder;
use Session;
use Auth;
class Syncronize extends Controller
{
    public function __construct(){
        /// $this->middleware('guest')->except('logout');
         session_start();    
         ini_set('max_execution_time', 0);
     }

    public function syncrecord(){
        if(Auth::user()->type === 'Encoder'){
            $token = Validator::validateToken(Auth::user()->api_token);
            if($token){     
                // check internet connections
                $connected = @fopen("http://www.google.com:80/","r"); 
                //  get encoders data
                $encModel = new ModelEncoder();
                $encodersData = $encModel->getEncoderData(Auth::user()->user_id);

                if ($connected){  
                    $is_conn = ''; //action when connected
                    fclose($connected); 

                    return view('encoder/encoder_syncronize', [
                        'type'=>'Encoder',
                        'encodersData'=>$encodersData, 
                        'no_internet'=>''
                    ]);
                }else{
                    $is_conn = 'No internet connection'; //action in connection failure 
                    return view('encoder/encoder_syncronize', ['type'=>'Encoder', 'encodersData'=>$encodersData,'no_internet'=>$is_conn]);
                } 

            }else{ return redirect('/invalid_url'); } //auto logout if token not match
        }else{
            return Validator::redirectURL(Auth::user()->type);
        }
    }

    public function syncUsersRecord(Request $request){
        if(Auth::user()->user_id == $request->encoders_id){
            $model = new ModelSync(); 
            if($request->table =='usersData'){
                $user =  $model->syncronizeusersTable();    
                if($user){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='notificationsData'){
                $notif =  $model->syncronizeaccount_notificationTable();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='appointmentListData'){
                $notif =  $model->syncronizeappointment_list();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                } 
            }else if($request->table =='appointmentSettingsData'){
                $notif =  $model->syncronizeappointment_settings();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='clinicsData'){
                $notif =  $model->syncronizeclinic();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='doctorsData'){
                $notif =  $model->syncronizedoctors();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='prescriptionsData'){
                $notif =  $model->syncronizedoctors_prescription();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='doctorsNotesData'){
                $notif =  $model->syncronizenotes();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='doctorsTreatmentPlan'){
                $notif =  $model->doctorsTreatmentPlan();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='encodersData'){
                $notif =  $model->syncronizeencoder();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='imagingData'){
                $notif =  $model->syncronizeimaging();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='imagingcenterData'){
                $notif =  $model->syncronizeimaging_center();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='laboratoryData'){
                $notif =  $model->syncronizelaboratory();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='laboratoryListData'){
                $notif =  $model->syncronizelaboratory_list();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='managementData'){
                $notif =  $model->syncronizemanagement();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='messagesData'){
                $notif =  $model->syncronizemessages();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='patientsData'){
                $notif =  $model->syncronizepatients();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='pghData'){
                $notif =  $model->syncronizepgh();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='phData'){
                $notif =  $model->syncronizeph();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='plhData'){
                $notif =  $model->syncronizeplh();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='pphData'){
                $notif =  $model->syncronizepph();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='prhData'){
                $notif =  $model->syncronizeprh();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='pthData'){
                $notif =  $model->syncronizepth();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='bugData'){
                $notif =  $model->syncronizebug();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='logsData'){
                $notif =  $model->syncronizelogs();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='patientHistoryAttachment'){ 
                $notif =  $model->patient_history_attachment();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='pharmacy'){ 
                $notif =  $model->pharmacy();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='pharmacyclinic_history'){ 
                $notif =  $model->pharmacyclinic_history();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='pharmacyclinic_inventory'){ 
                $notif =  $model->pharmacyclinic_inventory();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='pharmacyclinic_products'){ 
                $notif =  $model->pharmacyclinic_products();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='pharmacyclinic_receipt'){ 
                $notif =  $model->pharmacyclinic_receipt();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='pharmacyclinic_sales'){ 
                $notif =  $model->pharmacyclinic_sales();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='billing'){ 
                $notif =  $model->billing();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='billing_payment_history'){ 
                $notif =  $model->billing_payment_history();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='billing_statement_cart'){ 
                $notif =  $model->billing_statement_cart();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='billing_statement'){ 
                $notif =  $model->billing_statement();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='billing_receipt'){ 
                $notif =  $model->billing_receipt();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_sodium'){ 
                $notif =  $model->patients_history_sodium();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_protein'){ 
                $notif =  $model->patients_history_protein();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_potassium'){ 
                $notif =  $model->patients_history_potassium();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_magnessium'){ 
                $notif =  $model->patients_history_magnessium();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_lithium'){ 
                $notif =  $model->patients_history_lithium();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_ldl'){ 
                $notif =  $model->patients_history_ldl();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_hdl'){ 
                $notif =  $model->patients_history_hdl();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_creatinine'){ 
                $notif =  $model->patients_history_creatinine();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_chloride'){ 
                $notif =  $model->patients_history_chloride();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='patients_history_calcium'){ 
                $notif =  $model->patients_history_calcium();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='users_unemail_accounts'){ 
                $notif =  $model->users_unemail_accounts();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='virtual_clinic'){ 
                $notif =  $model->virtual_clinic();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='virtual_clinic_services'){ 
                $notif =  $model->virtual_clinic_services();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else if($request->table =='virtual_is_online'){ 
                $notif =  $model->virtual_is_online();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='bill_list'){ 
                $notif =  $model->bill_list();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='syncronize_patients_family_history'){ 
                $notif =  $model->syncronize_patients_family_history();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }else if($request->table =='pharmacyclinic_products_package'){ 
                $notif =  $model->pharmacyclinic_products_package();    
                if($notif){
                    return response()->json(['msg'=>'success']);
                }else{
                    return response()->json(['msg'=>'sync-error']);
                }
            }
            else{
                return redirect(404); // pagenotfound
            }
            
            
            
        }else{
            if(Auth::check()){ 
                $connected = @fopen("http://www.google.com:80/","r");  

                if (!$connected){
                    return response()->json(['msg'=>'no-internet']);
                } 
            }else{
                return response()->json(['msg'=>'account-inv']);
            }
            echo $request->encoders_id;
            // return response()->json(['msg'=>'account-inv']);
        }
    }

}
