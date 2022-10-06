<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Ride};
use Carbon\Carbon;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:upcomingNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $upcomingTime = Date("Y-m-d H:i:s",strtotime('+1 hour'));
        // dd(Date("Y-m-d H:i:s"));
        $rides = Ride::whereBetween('schedule_start_time',[Date("Y-m-d H:i:s"),$upcomingTime])->where('type','schedule')
        ->whereIn('status',['accepted','confirmed'])
        ->with(['driver','rider','rideLocations','rideLocations.children'])
        ->get();
        if($rides->count() > 0){
            foreach($rides as $ride){
                if($ride){
                    $title = 'You have a schedule ride from ' . $ride->rider->username;
                    $body = 'You have a schedule ride from ' . $ride->rider->username;
                    SendNotification($ride->rider->device_id, $title, $body,(array)$ride);
                    saveNotification($title,$body,'ride_request',$ride->driver->id,$ride->rider->id);
                }if($ride){
                    $title = 'You have a schedule ride from ' . $ride->driver->username;
                    $body = 'You have a schedule ride from ' . $ride->driver->username;
                    SendNotification($ride->driver->device_id, $title, $body,(array)$ride);
                    saveNotification($title,$body,'ride_request',$ride->rider->id,$ride->driver->id);
                }
            
        }
        }
        
        \Log::info("Schedule ride notification been sent successfully!");
        $this->info('Schedule ride notification been sent successfully!');
        
        
    }
}
