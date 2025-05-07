<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Product;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index()
    {
        return view('milestone.index');
    }

    public function getDataRewards(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $query = Milestone::orderBy('flames');

        // Aplicar filtros si se proporcionan

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $milestones = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $milestones as $milestone )
        {
            array_push($array, [
                "id" => $milestone->id,
                "title" => $milestone->title,
                "description" => $milestone->description,
                "flames" => $milestone->flames,
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $array, 'pagination' => $pagination];
    }

    public function create()
    {
        $products = Product::where('enable_status', 1)->get();
        return view('milestone.create', compact('products'));
    }
}
