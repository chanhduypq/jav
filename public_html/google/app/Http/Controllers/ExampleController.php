<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public $totalPages = 10;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function search(Request $request)
    {
        $result = [];
        $params = [];
        $toIndex = 10;

        if ($searchTerms = $request->post('search_terms')) {

            $searchTermsFiltred = urlencode(str_replace(' ', '+', trim($searchTerms)));

            $toIndex = $request->post('to_index') ?? 10;
            
            $key = "AIzaSyB16wvV51-FSuB4n5dbGgNqtxLuRWh5z8s";
            $cx = "007043409519568967944:zqts7n3gnc4";

            $url = "https://www.googleapis.com/customsearch/v1?start={$toIndex}&key={$key}&cx={$cx}&q={$searchTermsFiltred}";

            $ch = curl_init($url);
            curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , false );
            curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
            $result = curl_exec($ch);        
            curl_close($ch);

            $result = json_decode($result);
            
            $url = "https://api.duckduckgo.com/?q={$searchTermsFiltred}&format=json&pretty=1";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result1 = curl_exec($ch);
            curl_close($ch);

            $result1 = json_decode($result1, true);
            if (isset($result1['RelatedTopics']) && count($result1['RelatedTopics']) > 0) {
                $result1 = $result1['RelatedTopics'];
            } else {
                $result1 = null;
            }

            // echo "<pre>";
            // print_r($result); die();

            if (isset($result->error) && $result->error->code == 400) {
                return view('home', [
                    'enabled_search' => true,
                    'result' => null,
                    'result1' => $result1,
                    'search_terms' => $searchTerms ?? '',
                    'next_page' => null,
                    'to_index' => $toIndex,
                    'clear_search' => true,
                    'error' => 'Bad request',
                    'total_pages' => $this->totalPages,
                    'real_next_page' => $nextPage ?? true,
                ]);
            }

            if (!isset($result->items)) {
                return view('home', [
                    'enabled_search' => $toIndex == 10 && $result ? true : false,
                    'result' => null,
                    'result1' => $result1,
                    'search_terms' => $searchTerms ?? '',
                    'next_page' => null,
                    'real_next_page' => $nextPage ?? false,
                    'to_index' => $toIndex,
                    'clear_search' => true,
                    'total_pages' => $this->totalPages,
                ]);
            }

            $nextPageArray = $result->queries->nextPage;

            $nextPage = false;

            if ($nextPageArray && sizeof($nextPageArray) > 0) {
                $nextPage = true;
            }

            // echo "<pre>";
            // print_r($result);
            // die();;
        }

        return view('home', [
            'result' => $result ?? null,
            'result1' => $result1 ?? null,
            'search_terms' => $searchTerms ?? '',
            'params' => $params,
            'next_page' => $nextPage ?? 11,
            'real_next_page' => $nextPage ?? false,
            'enabled_search' => true,
            'total_pages' => $this->totalPages,
            'to_index' => $toIndex,
            'clear_search' => true,
        ]);
    }

}
