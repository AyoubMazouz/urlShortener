<?php

namespace App\Http\Controllers;

use App\Models\Urls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Url extends Controller
{
    public function index()
    {
        $urls = Urls::all();

        $response = [
            "ok" => true,
            "data" => $urls
        ];

        return response(json_encode($response), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['url' => 'required|url']);

        if ($validator->fails()) {
            $response = ["ok" => false, "message" => "Invalid URL"];
            return response(json_encode($response), 400);
        }

        $url = $request->input('url');

        $oldUrl = Urls::where('original_url', $url)->first();

        if ($oldUrl) {
            $response = ["ok" => true, "message" => "Url Already Exists", "data" => $oldUrl];
            return response(json_encode($response), 200);
        }

        $newUrl = Urls::create([
            "original_url" => $url,
            "code" => $this->generateShortCode($url)
        ]);

        $response = ["ok" => true, "message" => "Url Created", "data" => $newUrl];

        return response(json_encode($response), 200);
    }

    public function redirect($code)
    {
        $url = Urls::where('code', $code)->first();

        return redirect($url->original_url);
    }

    public function generateShortCode($value)
    {
        $hash = md5($value . microtime());
        return substr($hash, 0, 10);
    }
}
