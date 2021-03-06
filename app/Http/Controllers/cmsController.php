<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PosnetkiResource as PosnetkiResource;
use App\posnetki;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class cmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->session()->get('key') == true){
            return redirect('/login');
        }
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->input('type') && $request->input('sendByUser')){
            switch($request->input('type')){
                case 'changeOrder':
                    //Update selected record in database
                    $newIndex = $request->input('newIndex');
                    $zaporedjeArray = $request->input('zaporedjeArray');
                    $indexOfElementsArray = $request->input('indexOfElementsArray');

                    DB::update('UPDATE posnetki set zaporedje = ? WHERE idPosnetki = ?',
                        [$newIndex, $request->input('id')]
                    );

                    for($i = 0; $i < count($zaporedjeArray); $i++){
                        DB::update('update posnetki set zaporedje = ? where idPosnetki = ?', [$zaporedjeArray[$i], $indexOfElementsArray[$i]]);
                    }

                    return PosnetkiResource::collection(Posnetki::orderBy('zaporedje')->get());

                case 'delete':
                    $idPosnetkov = $request->input('idPosnetkov');
                    $thumbnailName = $request->input('thumbnailName');
                    $id = $request->input('id');

                    //Delete record from database
                    Storage::delete('public/storage/'.$thumbnailName);

                    DB::delete('delete from posnetki where idPosnetki = ?', [$id]);

                    for($i = 0; $i < count($idPosnetkov); $i++){
                        DB::update('update posnetki set zaporedje = ? where idPosnetki = ?', [$i+1, $idPosnetkov[$i]]);
                    }

                    return PosnetkiResource::collection(Posnetki::orderBy('zaporedje')->get());

                case 'add':

                   $image = $request->file('image');

                   $extenstion = $image->getClientOriginalExtension();
                   $imageName = $request->input('imageName');

                   $thumbnailName = $imageName.".".$extenstion;

                   //Add data to database
                   DB::insert('insert into posnetki (naslovPosnetka, opisPosnetka, videoLink, thumbnail, naslovPosnetkaApi, zaporedje) values (?,?,?,?,?,?)',
                   [$request->input('naslovPosnetka'), $request->input('opisPosnetka'), $request->input('videoLink'),  $thumbnailName, $request->input('apiNaslovPosnetka'), $request->input('zaporedje')]);

                   //Stores image into public storage
                   Storage::putFileAs('public', $image, $thumbnailName);

                   return PosnetkiResource::collection(Posnetki::orderBy('zaporedje')->get());

                case 'changeData':
                    //Send data
                    $naslovPosnetka = $request->input('naslovPosnetka');
                    $opisPosnetka = $request->input('opisPosnetka');
                    $naslovPosnetkaApi = $request->input('naslovPosnetkaApi');
                    $videoLink = $request->input('videoLink');
                    $id = $request->input('id');


                    DB::update('update posnetki set naslovPosnetka = ?, opisPosnetka = ?, videoLink = ?, naslovPosnetkaApi = ? where idPosnetki = ?',
                    [$naslovPosnetka, $opisPosnetka, $videoLink, $naslovPosnetkaApi, $id]);

                    return PosnetkiResource::collection(Posnetki::orderBy('zaporedje')->get());
                }

        }
        return "Error";

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
