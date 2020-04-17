<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use App\Sos;
use App\Ask;

class Nearby extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nearby:cache {--user=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search nearby SOSs for each user in the watch list and store in cache.';

    private const MILES_PER_METRE = 0.000621371192;
    private const KM_PER_METRE = 0.001;
    
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
        if ($this->option('user')) {
            $responder = User::find($this->option('user'));
            $respoders = new Collection();
            $respoders->push($responder);
        } 
        
        
        //https://tighten.co/blog/a-mysql-distance-function-you-should-know-about/
        foreach ($responders as $responder) {
            
            /*$userId = $responder->id;
            dd(Sos::where('status', '=', Sos::STATUS_COMPLETED )
            ->whereRaw(
                "responded_by = {$userId} OR created_by = {$userId}"
            )->get());
            */
            
            
            $asksQuery = \DB::table('asks')
                ->leftJoin('users as creator', 'asks.user_id', '=', 'creator.id')
                //->leftJoin('users as responder', 'sos.responded_by', '=', 'responder.id')
                
                ->join('users as responder', function ($join) use ($responder) {
                    $join
                        ->on('creator.id', '<>', 'responder.id')
                        ->where('responder.id' , '=', $responder->id);
                })
            
                //->join('users as responder', 'creator.id', '<>', 'responder.id')
                /*->selectRaw('
                    ST_Distance_Sphere(creator.longlat,responder.longlat) AS dist
                ')*/
                ->select([
                    'asks.*',
                    'creator.name as creator.name',
                    'creator.address as creator.address',
                ])
                ->where('asks.status', Ask::STATUS_PENDING)
                ->whereRaw('
                    ST_Distance_Sphere(
                        creator.longlat,
                        responder.longlat
                    ) <= 10000'
                )
                ->orderBy('asks.needed_by') //asc soonest first
                ->orderBy('asks.created_at') //asc oldest first
                ->limit(10);
                
            $asksCollection = $asksQuery->get();
            
            //Cache::put(User::CACHE_KEY_NEARBY. '-' . $responder->id, serialize($sosCollection));
            $responder->putNearbyCache($asksCollection);
            echo "Cache {$asksCollection->count()} records for user {$responder->id}\n";
        }
    }
}
