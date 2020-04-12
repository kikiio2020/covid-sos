<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use App\Sos;

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
        //https://tighten.co/blog/a-mysql-distance-function-you-should-know-about/
        foreach ($responders as $responder) {
            
            $userId = $responder->id;
            dd(Sos::where('status', '=', Sos::STATUS_COMPLETED )
            ->whereRaw(
                "responded_by = {$userId} OR created_by = {$userId}"
            )->get());
            
            
            
            $sosQuery = \DB::table('sos')
                ->leftJoin('users as creator', 'sos.created_by', '=', 'creator.id')
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
                    'sos.*',
                    'creator.name as creator.name',
                    'creator.address as creator.address',
                ])
                ->whereRaw('
                    ST_Distance_Sphere(
                        creator.longlat,
                        responder.longlat
                    ) <= 10000'
                )
                ->orderBy('sos.needed_by') //asc soonest first
                ->orderBy('sos.created_at') //asc oldest first
                ->limit(10);
                
            $sosCollection = $sosQuery->get();
            
            //Cache::put(User::CACHE_KEY_NEARBY. '-' . $responder->id, serialize($sosCollection));
            $responder->putNearbyCache($sosCollection);
            echo "Cache {$sosCollection->count()} records for user {$responder->id}\n";
        }
    }
}
