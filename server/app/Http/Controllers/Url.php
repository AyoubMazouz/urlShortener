<?php

namespace App\Http\Controllers;

use App\Models\Urls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), ['code' => 'required']);

        if ($validator->fails()) {
            $response = ["ok" => false, "message" => "Invalid Code"];
            return response(json_encode($response), 400);
        }

        $code = $request->input('code');

        $url = Urls::where('code', $code)->first();

        if (!$url)
            throw new ResourceNotFoundException("Url Not Found");

        $url->delete();

        $response = ["ok" => true, "message" => "Url Deleted"];

        return response(json_encode($response), 200);
    }

    public function redirect($code)
    {
        $url = Urls::where('code', $code)->first();

        if (!$url)
            throw new ResourceNotFoundException("Url Not Found");

        $url->increment('visits_count');

        return redirect($url->original_url);
    }

    public function generateShortCode($value)
    {
        $hash = md5($value . microtime());
        return substr($hash, 0, 10);
    }
}
