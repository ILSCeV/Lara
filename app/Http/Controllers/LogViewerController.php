<?php
namespace Lara\Http\Controllers;

//use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;


class LogViewerController extends Controller
{

    public function index()
    {
        if (Request::input('l')) {
            \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer::setFile(base64_decode(Request::input('l')));
        }

        if (Request::input('dl')) {
            return Response::download(storage_path() . '/logs/' . base64_decode(Request::input('dl')));
        } elseif (Request::has('del')) {
            File::delete(storage_path() . '/logs/' . base64_decode(Request::input('del')));
            return Redirect::to(Request::url());
        }

        $logs = \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer::all();

        return View::make('log', [
            'logs' => $logs,
            'files' => \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer::getFiles(true),
            'current_file' => \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer::getFileName()
        ]);
    }

}
