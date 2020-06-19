<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
class cronPremium extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Premium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Premium and trial premium every day';

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
        //
        $now = time();
        // $trial_premium = User::where('is_trial',1)->get();
        // $premium = User::where('vip','premium')->whereIn('is_trial',[0,2])->get();
        // foreach ($trial_premium as $key => $value) {
        //     $end = (int)$value->trial_start_at + 30*24*3600;
        //     if($now > $end){
        //         User::where('id',$value->id)->update(['is_trial'=>2,'vip'=>'default']);
        //     }        
        // }
        // foreach ($premium as $key => $value) {
        //     $end = (int)$value->premium_start_at + 30*24*3600;
        //     if($now > $end){
        //         User::where('id',$value->id)->update(['vip'=>'default']);
        //     }        
        // }
        $user = User::get();
        foreach ($user as $key => $value) {
            if($value->setting_json){
                $setting = $value->setting;
                if($setting->swap_today){
                    $setting->category = json_encode($setting->category);
                    $setting->color = json_encode($setting->color);
                    $setting->brand = json_encode($setting->brand);
                    if(!isset($setting->time_swap_today) || $now > (int)$setting->time_swap_today + 86400)                 
                        $setting->swap_today = 0;         
                    $data = json_encode($setting);
                    $value->setting_json = $data;
                    $value->save();
                }
            }
        }
        
        User::where('id',1)->update(['type_role'=>'admin']);
    }
}
