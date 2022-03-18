<?php

namespace App\Http\Controllers;

use App\Http\Libraries\BaseApi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $baseApi = new BaseApi;
        $users = $baseApi->index('/user');

        return view('users.index')->with(['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payload = [
            'firstName' => $request->input('nama_depan'),
            'lastName' => $request->input('nama_belakang'),
            'email' => $request->input('email'),
        ];

        $baseApi = new BaseApi;
        $response = $baseApi->create('/user/create', $payload);

        // handle jika request API nya gagal
        // diblade nanti bisa ditambahkan toast alert
        if ($response->failed()) {
            // $response->json agar response dari API bisa di akses sebagai array
            $errors = $response->json('data');

            return redirect()->route('users.create')->withErrors($errors)->withInput();
        }

        return redirect()->route('users.index')->with('message', 'Data berhasil disimpan',);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //kalian bisa coba untuk dd($response) untuk test apakah api nya sudah benar atau belum
        //sesuai documentasi api detail user akan menshow data detail seperti `email` yg tidak dimunculkan di api list index
        $response = (new BaseApi)->detail('/user', $id);
        return view('users.edit')->with([
            'user' => $response->json()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = (new BaseApi)->detail('/user', $id);

        return view('users.edit')->with(['user' => $response]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //column yg bisa di update sesuai dengan documentasi dummyapi.io hanyalah
        // `fisrtName`, `lastName`
        $payload = [
            'firstName' => $request->input('nama_depan'),
            'lastName' => $request->input('nama_belakang'),
        ];

        $response = (new BaseApi)->update('/user', $id, $payload);

        if ($response->failed()) {
            $errors = $response->json('data');

            return redirect()->route('users.edit', $id)->withErrors($errors)->withInput();
        }

        return redirect()->route('users.index')->with('message', 'Data berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = (new BaseApi)->delete('/user', $id);

        if ($response->failed()) {
            return redirect()->back()->with('message', 'Data gagal dihapus');
        }

        return redirect()->back()->with('message', 'Data berhasil dihapus');
    }
}
