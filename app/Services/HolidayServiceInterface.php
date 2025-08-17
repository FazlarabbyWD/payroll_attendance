<?php

namespace App\Services;

use App\Models\Holiday;
use App\Http\Requests\HolidayStoreRequest;

interface HolidayServiceInterface
{
    public function getAllHolidays();
    public function createHoliday(HolidayStoreRequest $request): Holiday;
    public function getHolidayById(int $holiday): ?Holiday;
    public function updateHoliday(Holiday $holiday, HolidayStoreRequest $request): Holiday;
    public function deleteHoliday(Holiday $holiday): bool;
    // public function forceDeleteHoliday(int $id): bool;
    // public function restoreHoliday(int $id): bool;
}
