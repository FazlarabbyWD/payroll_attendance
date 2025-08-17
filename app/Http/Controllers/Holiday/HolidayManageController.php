<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayStoreRequest;
use App\Models\Holiday;
use App\Services\HolidayServiceInterface;
use Illuminate\Support\Facades\Log;


class HolidayManageController extends Controller
{
    protected $holidayService;

    public function __construct(HolidayServiceInterface $holidayService)
    {
        $this->holidayService = $holidayService;
    }


    public function index()
    {
        try {
            $holidays = $this->holidayService->getAllHolidays();
            return view('holidays.index', compact('holidays'));
        } catch (\Exception $e) {
            Log::error($e);

        }
    }


    public function store(HolidayStoreRequest $request)
    {

        try {
            $holiday = $this->holidayService->createHoliday($request);
            return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'Failed to create holiday.')->withInput();
        }
    }


    public function show($id)
    {
        try {
            $holiday = $this->holidayService->getHolidayById($id);

            if (!$holiday) {
                return view('errors.404', ['message' => 'Holiday not found.']);
            }

            return view('holidays.show', compact('holiday'));
        } catch (\Exception $e) {
            Log::error($e);
            return view('errors.500', ['message' => 'Failed to retrieve holiday.']);
        }
    }


    public function edit($holiday)
    {
        try {
            $holiday = $this->holidayService->getHolidayById($holiday);

            if (!$holiday) {
                return view('errors.404', ['message' => 'Holiday not found.']);
            }

            return view('holidays.edit', compact('holiday'));
        } catch (\Exception $e) {
            Log::error($e);
            return view('errors.500', ['message' => 'Failed to retrieve holiday.']);
        }
    }



    public function update(HolidayStoreRequest $request, $holiday)
    {

        try {
            $holiday = $this->holidayService->getHolidayById($holiday);

            if (!$holiday) {
                return view('errors.404', ['message' => 'Holiday not found.']);
            }

            $this->holidayService->updateHoliday($holiday, $request);
            return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'Failed to update holiday.')->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            $holiday = $this->holidayService->getHolidayById($id);

            if (!$holiday) {
                return view('errors.404', ['message' => 'Holiday not found.']);
            }

            $this->holidayService->deleteHoliday($holiday);
            return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'Failed to delete holiday.');
        }
    }


    public function forceDestroy($id)
    {
        try {
            $this->holidayService->forceDeleteHoliday($id);
            return redirect()->route('holidays.index')->with('success', 'Holiday permanently deleted.');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'Failed to permanently delete holiday.');
        }
    }


    public function restore($id)
    {
        try {
            $this->holidayService->restoreHoliday($id);
            return redirect()->route('holidays.index')->with('success', 'Holiday restored successfully.');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'Failed to restore holiday.');
        }
    }
}
