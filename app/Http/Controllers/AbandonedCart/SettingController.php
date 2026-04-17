<?php

namespace App\Http\Controllers\AbandonedCart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('backend.abandoned_cart.settings');
    }

    public function update(Request $request)
    {
        foreach ($request->types as $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        flash(translate("Settings updated successfully"))->success();

        return back();
    }

    public function overWriteEnvFile($type, $val)
    {
        if (env('DEMO_MODE') != 'On') {
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = is_bool($val) ? ($val ? 'true' : 'false') : '"'.trim($val).'"';
                $content = file_get_contents($path);

                if (is_numeric(strpos($content, $type)) && strpos($content, $type) >= 0) {
                    $content = preg_replace(
                        '/'.$type.'\s*=\s*(?:".*?"|\S+)/',
                        $type.'='.$val,
                        $content
                    );
                } else {
                    $content .= "\r\n".$type.'='.$val;
                }

                file_put_contents($path, $content);
            }
        }
    }
}
