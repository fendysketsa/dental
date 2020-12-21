<?php

namespace App\Http\Controllers\Api\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\Member\Member;
use App\Models\User;
use App\Http\Resources\Member as MemberResource;
use App\Http\Resources\MemberCollection;

class MemberController extends Controller
{

    public function index(Request $request)
    {
        //return MemberResource::collection(Member::all());
        return new MemberCollection(Member::paginate(2));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'email' => 'required|unique:member'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60),
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'Berhasil menambah data',
            'data' => Member::create($request->all())
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        return new MemberCollection(collect([$member]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $member->fill($request->all());
        $member->save();

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil update data member'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil menghapus data member'
        ]);
    }
}
