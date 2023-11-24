<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewOrganismRequest;
use App\Models\Organism;
use App\Models\Sample;
use Illuminate\Support\Facades\DB;

/**
 * Example of controller for the Challenge
 */
class BiomeController extends Controller
{


    /**
     * Returns a list of samples
     */
    public function listSamples()
    {
        $samples = Sample::query()
            ->with('crop:id,name')
            ->withCount('abundances')
            ->get();

        $response = [];
        foreach ($samples as $sample) {
            $s = $sample->only(['code', 'abundances_count']);
            $s['crop'] = $sample['crop']['name'];
            $response[] = $s;
        }

        return $response;
    }

    /**
     * Creates a new organism
     */
    public function newOrganism(NewOrganismRequest $request)
    {
        try{
            Organism::create($request->only(['genus','species']));
        }catch(\Exception $ex){
            return response()->json([],500);
        }
        return response()->json();
    }

    /**
     * Returns a paginated list of organisms
     */
    public function listOrganisms()
    {
        return Organism::withCount('samples')->paginate(10);
    }

    /**
     * Returns the top list of organisms
     */
    public function listOrganismsTop10()
    {
        $organisms = Organism::with('samples.crop')
            ->withCount('samples')
            ->orderBy('samples_count', 'desc')
            ->limit(10)->get();

        foreach($organisms as $organism){
            $crops = [];
            foreach($organism->samples as $sample){
                $cropId = $sample->crop->id;
                if(!isset($crops[$cropId])){
                    $crops[$cropId] = $sample->crop->toArray();
                    $crops[$cropId]['count'] = 0;
                }
                $crops[$cropId]['count']++;
            }
            $organism->common_crops = collect($crops)->sortByDesc('count')->chunk(3)[0];
        }

        return $organisms;
    }

}
