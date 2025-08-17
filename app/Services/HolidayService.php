<?php

namespace App\Services;

use App\Http\Requests\HolidayStoreRequest;
use App\Models\Holiday;
use App\Repositories\HolidayRepositoryInterface;
use Carbon\Carbon;

class HolidayService implements HolidayServiceInterface
{
    protected $holidayRepository;

    public function __construct(HolidayRepositoryInterface $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }

    public function getAllHolidays()
    {
        return $this->holidayRepository->getAll();
    }

    public function createHoliday(HolidayStoreRequest $request): Holiday
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);

        return $this->holidayRepository->create($data);
    }

    public function getHolidayById(int $id): ?Holiday
    {
        return $this->holidayRepository->find($id);
    }

    public function updateHoliday(Holiday $holiday, HolidayStoreRequest $request): Holiday
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);
        return $this->holidayRepository->update($holiday, $data);
    }

    public function deleteHoliday(Holiday $holiday): bool
    {

        return $this->holidayRepository->delete($holiday);
    }

    // public function forceDeleteHoliday(int $id): bool
    // {
    //     return $this->holidayRepository->forceDelete($id);
    // }

    // public function restoreHoliday(int $id): bool
    // {
    //     return $this->holidayRepository->restore($id);
    // }
}
