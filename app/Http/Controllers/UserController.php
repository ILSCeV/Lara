<?php

namespace Lara\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Input;
use Lara\Http\Middleware\ClOnly;
use Lara\Logging;
use Lara\Role;
use Lara\Section;
use Lara\Status;
use Lara\User;
use Lara\Utilities;
use Lara\utilities\RoleUtility;
use Redirect;
use View;

class UserController extends Controller
{

    public function __construct()
    {
       $this->middleware(ClOnly::class);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(User $user,array $data)
    {
        return \Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,id,'.$user->id,
            'section' => [
                'required',
                Rule::in(
                    Section::all()->map(
                        function(Section $section) { return $section->id;}
                    )->toArray()
                )
            ],
            'status' => ['required', Rule::in(Status::ACTIVE)]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users = User::with('section')
            ->get()
            ->sortBy(function (User $user) {
                return sprintf('%-12s%s%s%s', Auth::user()->section->id != $user->section->id,$user->section->title, $user->name, $user->status);
            });

        return View::make('user.index', compact('users'));
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
        //
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
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        /** @var User $editUser */
        $user = User::findOrFail($id);

        $permissionsPersection = [];
        $sectionQuery = Section::query();
        if(!Auth::user()->is(RoleUtility::PRIVILEGE_ADMINISTRATOR)){
            $sectionQuery->whereIn('id',Auth::user()->getSectionsIdForRoles(RoleUtility::PRIVILEGE_CL));
        }

        foreach ($sectionQuery->get() as $section){
            $roles = Role::query()->where('section_id','=',$section->id)->get();
            $permissionsPersection[$section->id] = $roles;
        }

        return View::make('user.edituser',compact('user','permissionsPersection'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        //$this->authorize('update', $user);

        if (!Auth::user()->can('update',$user) ) {
            Utilities::error(trans('mainLang.accessDenied'));
            return Redirect::back();
        }

        $user->fill($request->all())->save();
        Utilities::success(trans('mainLang.update'));
        return Redirect::back();
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateData(Request $request, User $user)
    {
        if (!Auth::user()->can('update', $user)) {
            Utilities::error(trans('mainLang.accessDenied'));
            return Redirect::back();
        }
        $data = [];
        if(Auth::user()->is(RoleUtility::PRIVILEGE_ADMINISTRATOR) || Auth::user()->hasPermissionsInSection(Section::findOrFail($user->section)->first(),RoleUtility::PRIVILEGE_CL)){
            $validator = $this->validator($user, Input::all());
            if ($validator->fails()) {
                Utilities::error(trans('mainLang.changesWereReset'));
                return Redirect::back()->withErrors($validator);
            }
            $data['name'] = Input::get('name');
            $data['email'] = Input::get('email');
            $data['section_id'] = Input::get('section');
            $data['status'] = Input::get('status');

        }
        if(Auth::user()->is(RoleUtility::PRIVILEGE_ADMINISTRATOR)){
            $sectionIds =  Section::all()->map(function (Section $section){
                return $section->id;
            });
        } else {
            $sectionIds = Auth::user()->getSectionsIdForRoles(RoleUtility::PRIVILEGE_CL);
        }
        $assignedRoleIds = [];
        $unassignedRoleIds = [];
        foreach ($sectionIds as $sectionId){
            $lAssignedRoleIds = explode(',',\Input::get('role-assigned-section-'.$sectionId));
            $assignedRoleIds = array_merge($lAssignedRoleIds, $assignedRoleIds);

            $lUnassignedRoleIds = explode(',',\Input::get('role-unassigned-section-'.$sectionId));
            $unassignedRoleIds = array_merge($lUnassignedRoleIds, $unassignedRoleIds);
        }

        $assignedRoles = Role::query()->whereIn('id', $assignedRoleIds)->get();
        $unassignedRoles = Role::query()->whereIn('id', $unassignedRoleIds)->get();

        $user->fill($data);
        $changedSection = $user->isDirty('section_id');
        $user->save();

        $assignedRoles->each(function(Role $role) use ($user) {
            if(!Auth::user()->can('assign',$role)){
                \Log::warning(trans('mainLang.accessDenied') . ' ' . $role->name);
            } else {
                $user->roles()->attach($role);
            }
        });
        $unassignedRoles->each(function(Role $role) use ($user) {
            if(!Auth::user()->can('remove',$role)){
                \Log::warning(trans('mainLang.accessDenied') . ' ' . $role->name . ' ' . $user->name);
            } else {
                $user->roles()->detach($role);
            }
        });

        if ($changedSection) {
            Utilities::success(trans('mainLang.changesSaved') . trans('mainLang.sectionChanged'));
        }
        else {
            Utilities::success(trans('mainLang.changesSaved'));
        }
        return Redirect::back();
    }
}
