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
        $NUMBER_ROW_PERPAGE=10;
        $count=100;
        
        if ($request->post('page') && ctype_digit($request->post('page'))) {
            $page = $request->post('page');
        } else {
            $page = 1;
        }
        $offset = ($page - 1) * $NUMBER_ROW_PERPAGE;

        if ($searchTerms = $request->post('search_terms')) {

            $searchTermsFiltred = urlencode(str_replace(' ', '+', trim($searchTerms)));

            $toIndex = $request->post('to_index') ?? 10;
            
            $key = "AIzaSyB16wvV51-FSuB4n5dbGgNqtxLuRWh5z8s";
            $cx = "007043409519568967944:zqts7n3gnc4";

//            $url = "https://www.googleapis.com/customsearch/v1?start={$toIndex}&key={$key}&cx={$cx}&q={$searchTermsFiltred}";
            $url = "https://www.googleapis.com/customsearch/v1?start={$offset}&key={$key}&cx={$cx}&q={$searchTermsFiltred}";

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
                    'result' => null,
                    'result1' => $result1,
                    'search_terms' => $searchTerms ?? '',
                    'to_index' => $toIndex,
                    'error' => 'Bad request',
                    'total_pages' => $this->totalPages,
                    'page'=>$page,
                    'count'=>$count,
                    'NUMBER_ROW_PERPAGE'=>$NUMBER_ROW_PERPAGE,
                ]);
            }

            if (!isset($result->items)) {
                return view('home', [
                    'result' => null,
                    'result1' => $result1,
                    'search_terms' => $searchTerms ?? '',
                    'to_index' => $toIndex,
                    'total_pages' => $this->totalPages,
                    'page'=>$page,
                    'count'=>$count,
                    'NUMBER_ROW_PERPAGE'=>$NUMBER_ROW_PERPAGE,
                ]);
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
            'total_pages' => $this->totalPages,
            'to_index' => $toIndex,
            'page'=>$page,
            'count'=>$count,
            'NUMBER_ROW_PERPAGE'=>$NUMBER_ROW_PERPAGE,
        ]);
    }

}
