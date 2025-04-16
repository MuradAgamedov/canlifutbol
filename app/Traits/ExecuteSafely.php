<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use TranslateService;

trait ExecuteSafely
{
    public function executeSafely(callable $callback, $route = null, $ajax = null, $routeParams = [])
    {
        try {
            return $callback();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if ($ajax) {
                return response()->json(['error' => 'Bir xəta baş verdi: ' . $e->getMessage()]);
            } else {
                return redirect()->route($route, $routeParams)->with(
                    ['error' => 'Bir xəta baş verdi: ' . $e->getMessage()]
                );
            }
        }
    }
}
