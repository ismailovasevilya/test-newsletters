<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NewsLetterStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Inertia\Inertia;


class StatisticsController extends Controller
{
    public function index(Request $request) {
        $newsletters = Newsletter::with('clients')->withCount([
            'clients',
            'clients as delivered_clients_count' => function ($query) {
                $query->where('status', 'delivered');
            }
        ])->paginate($request->per_page);
        $statuses = NewsLetterStatusEnum::getAll();


        return Inertia::render('Statistics/Index', [
            'newsletters' => $newsletters,
            'statuses' => $statuses
        ]);
    }
}