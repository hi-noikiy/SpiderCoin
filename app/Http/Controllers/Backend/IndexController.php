<?php

namespace App\Http\Controllers\Backend;

use App\Facades\ActionRepository;
use App\Facades\MenuRepository;
use App\Facades\PermissionRepository;
use App\Facades\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = 1;
        $roles = 1;
        $actions = 1;
        $permissions = 1;

        return view('backend.index.index', compact('menus', 'roles', 'actions', 'permissions'));
    }
}
