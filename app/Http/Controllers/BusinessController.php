<?php

namespace App\Http\Controllers;

use App\Imports\BusinessesImport;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BusinessController extends Controller
{
    public function dashboard()
    {
        $total = Business::count();

        $duplicates = Business::where('is_duplicate', true)->count();

        $incomplete = Business::where(function ($query) {
            $query->whereNull('business_name')
                ->orWhere('business_name', '')
                ->orWhereNull('area')
                ->orWhere('area', '')
                ->orWhereNull('city')
                ->orWhere('city', '')
                ->orWhereNull('address')
                ->orWhere('address', '');
        })->count();

        return view('dashboard', compact('total', 'duplicates', 'incomplete'));
    }

    public function index()
    {
        $businesses = Business::latest()->paginate(20);
        return view('businesses.index', compact('businesses'));
    }

    public function showImportForm()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'import_mode' => 'required|in:latest,combined',
        ]);

        if ($request->import_mode === 'latest') {
            Business::truncate();
        }

        Excel::import(new BusinessesImport, $request->file('file'));

        $this->markDuplicates();

        if ($request->import_mode === 'latest') {
            $message = 'Latest file imported successfully. Old data removed.';
        } else {
            $message = 'New file imported successfully. Old and new data combined.';
        }

        return redirect()->back()->with('success', $message);
    }

    private function normalizeValue($value)
    {
        return strtolower(trim((string) $value));
    }

    private function markDuplicates()
    {
        Business::query()->update([
            'is_duplicate' => false,
            'duplicate_group_id' => null,
        ]);

        $allBusinesses = Business::all();

        $grouped = $allBusinesses->groupBy(function ($item) {
            return $this->normalizeValue($item->business_name) . '|' .
                   $this->normalizeValue($item->area) . '|' .
                   $this->normalizeValue($item->city) . '|' .
                   $this->normalizeValue($item->address);
        });

        $groupId = 1;

        foreach ($grouped as $key => $group) {
            if (
                $key === '|||' ||
                $group->count() < 2
            )   {
                continue;
            }   

            foreach ($group as $business) {
                $business->update([
                    'is_duplicate' => true,
                    'duplicate_group_id' => $groupId,
                ]);
            }

            $groupId++;
        }
    }

    public function duplicates()
    {
        $duplicateGroups = Business::where('is_duplicate', true)
            ->orderBy('duplicate_group_id')
            ->get()
            ->groupBy('duplicate_group_id');

        return view('businesses.duplicates', compact('duplicateGroups'));
    }

    public function mergeDuplicates($groupId)
    {
        $records = Business::where('duplicate_group_id', $groupId)->get();

        if ($records->count() < 2) {
            return redirect()->back()->with('success', 'No duplicate records to merge.');
        }

        $main = $records->first();

        foreach ($records->skip(1) as $record) {
            if (empty($main->mobile_no) && !empty($record->mobile_no)) {
                $main->mobile_no = $record->mobile_no;
            }

            if (empty($main->category) && !empty($record->category)) {
                $main->category = $record->category;
            }

            if (empty($main->sub_category) && !empty($record->sub_category)) {
                $main->sub_category = $record->sub_category;
            }

            if (empty($main->address) && !empty($record->address)) {
                $main->address = $record->address;
            }

            if (empty($main->area) && !empty($record->area)) {
                $main->area = $record->area;
            }

            if (empty($main->city) && !empty($record->city)) {
                $main->city = $record->city;
            }

            if (empty($main->business_name) && !empty($record->business_name)) {
                $main->business_name = $record->business_name;
            }

            $record->delete();
        }

        $main->is_duplicate = false;
        $main->duplicate_group_id = null;
        $main->save();

        $this->markDuplicates();

        return redirect()->back()->with('success', 'Duplicate group merged successfully.');
    }

    public function mergeAllDuplicates()
    {
        $groupIds = Business::whereNotNull('duplicate_group_id')
            ->where('is_duplicate', true)
            ->distinct()
            ->pluck('duplicate_group_id');

        foreach ($groupIds as $groupId) {
            $records = Business::where('duplicate_group_id', $groupId)->get();

            if ($records->count() < 2) {
                continue;
            }

            $main = $records->first();

            foreach ($records->skip(1) as $record) {
                if (empty($main->mobile_no) && !empty($record->mobile_no)) {
                    $main->mobile_no = $record->mobile_no;
                }

                if (empty($main->category) && !empty($record->category)) {
                    $main->category = $record->category;
                }

                if (empty($main->sub_category) && !empty($record->sub_category)) {
                    $main->sub_category = $record->sub_category;
                }

                if (empty($main->address) && !empty($record->address)) {
                    $main->address = $record->address;
                }

                if (empty($main->area) && !empty($record->area)) {
                    $main->area = $record->area;
                }

                if (empty($main->city) && !empty($record->city)) {
                    $main->city = $record->city;
                }

                if (empty($main->business_name) && !empty($record->business_name)) {
                    $main->business_name = $record->business_name;
                }

                $record->delete();
            }

            $main->is_duplicate = false;
            $main->duplicate_group_id = null;
            $main->save();
        }

        $this->markDuplicates();

        return redirect()->back()->with('success', 'All duplicate groups merged successfully.');
    }

    public function reports()
    {
        $totalNames = Business::count();

        $cityWise = Business::select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->orderBy('city')
            ->get();

        $categoryCityWise = Business::select('category', 'city', DB::raw('count(*) as total'))
            ->groupBy('category', 'city')
            ->orderBy('category')
            ->orderBy('city')
            ->get();

        $categoryAreaWise = Business::select('category', 'area', DB::raw('count(*) as total'))
            ->groupBy('category', 'area')
            ->orderBy('category')
            ->orderBy('area')
            ->get();

        $uniqueListing = Business::where('is_duplicate', false)->count();

        $duplicateListing = Business::where('is_duplicate', true)->count();

        $incompleteListing = Business::where(function ($query) {
            $query->whereNull('business_name')
                ->orWhere('business_name', '')
                ->orWhereNull('area')
                ->orWhere('area', '')
                ->orWhereNull('city')
                ->orWhere('city', '')
                ->orWhereNull('address')
                ->orWhere('address', '');
        })->count();

        return view('businesses.reports', compact(
            'totalNames',
            'cityWise',
            'categoryCityWise',
            'categoryAreaWise',
            'uniqueListing',
            'duplicateListing',
            'incompleteListing'
        ));
    }

    private function downloadCsv($filename, $headers, $rows)
    {
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($rows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function downloadCityWiseReport()
    {
        $data = Business::select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->orderBy('city')
            ->get();

        $rows = [];

        foreach ($data as $item) {
            $rows[] = [
                $item->city ?: 'N/A',
                $item->total,
            ];
        }

        return $this->downloadCsv('city_wise_report.csv', ['City', 'Total'], $rows);
    }

    public function downloadCategoryCityWiseReport()
    {
        $data = Business::select('category', 'city', DB::raw('count(*) as total'))
            ->groupBy('category', 'city')
            ->orderBy('category')
            ->orderBy('city')
            ->get();

        $rows = [];

        foreach ($data as $item) {
            $rows[] = [
                $item->category ?: 'N/A',
                $item->city ?: 'N/A',
                $item->total,
            ];
        }

        return $this->downloadCsv(
            'category_city_wise_report.csv',
            ['Category', 'City', 'Total'],
            $rows
        );
    }

    public function downloadCategoryAreaWiseReport()
    {
        $data = Business::select('category', 'area', DB::raw('count(*) as total'))
            ->groupBy('category', 'area')
            ->orderBy('category')
            ->orderBy('area')
            ->get();

        $rows = [];

        foreach ($data as $item) {
            $rows[] = [
                $item->category ?: 'N/A',
                $item->area ?: 'N/A',
                $item->total,
            ];
        }

        return $this->downloadCsv(
            'category_area_wise_report.csv',
            ['Category', 'Area', 'Total'],
            $rows
        );
    }

    public function downloadDuplicateReport()
    {
        $data = Business::where('is_duplicate', true)
            ->orderBy('duplicate_group_id')
            ->get();

        $rows = [];

        foreach ($data as $item) {
            $rows[] = [
                $item->id,
                $item->duplicate_group_id,
                $item->business_name,
                $item->area,
                $item->city,
                $item->mobile_no,
                $item->category,
                $item->sub_category,
                $item->address,
            ];
        }

        return $this->downloadCsv(
            'duplicate_listing.csv',
            ['ID', 'Group ID', 'Business Name', 'Area', 'City', 'Mobile', 'Category', 'Sub Category', 'Address'],
            $rows
        );
    }

    public function downloadIncompleteReport()
    {
        $data = Business::where(function ($query) {
            $query->whereNull('business_name')
                ->orWhere('business_name', '')
                ->orWhereNull('area')
                ->orWhere('area', '')
                ->orWhereNull('city')
                ->orWhere('city', '')
                ->orWhereNull('address')
                ->orWhere('address', '');
        })->get();

        $rows = [];

        foreach ($data as $item) {
            $rows[] = [
                $item->id,
                $item->business_name,
                $item->area,
                $item->city,
                $item->mobile_no,
                $item->category,
                $item->sub_category,
                $item->address,
            ];
        }

        return $this->downloadCsv(
            'incomplete_listing.csv',
            ['ID', 'Business Name', 'Area', 'City', 'Mobile', 'Category', 'Sub Category', 'Address'],
            $rows
        );
    }

    public function clearAllRecords()
    {
        Business::truncate();

        return redirect()->route('dashboard')->with('success', 'All records deleted successfully.');
    }

    public function downloadSummaryReport()
    {
        $totalNames = Business::count();
        $uniqueListing = Business::where('is_duplicate', false)->count();
        $duplicateListing = Business::where('is_duplicate', true)->count();

        $incompleteListing = Business::where(function ($query) {
            $query->whereNull('business_name')
                ->orWhere('business_name', '')
                ->orWhereNull('area')
                ->orWhere('area', '')
                ->orWhereNull('city')
                ->orWhere('city', '')
                ->orWhereNull('address')
                ->orWhere('address', '');
        })->count();

        $rows = [
            ['Total Names', $totalNames],
            ['Unique Listings', $uniqueListing],
            ['Duplicate Listings', $duplicateListing],
            ['Incomplete Listings', $incompleteListing],
        ];

        return $this->downloadCsv('summary_report.csv', ['Metric', 'Value'], $rows);
    }
}