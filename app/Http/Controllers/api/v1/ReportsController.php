<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Attention;
use Illuminate\Http\Request;
use App\Http\Controllers\api\v1\AttentionController;

class ReportsController extends Controller
{
    //

    public function atenciones(Request $request) {
        $attentions = (new AttentionController())->getAttentions($request);

        foreach ($attentions as $attention) {

        }
    }
}
