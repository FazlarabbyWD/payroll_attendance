<?php
namespace App\Providers;

use App\Services\BankService;
use App\Services\UserService;
use App\Services\LeaveService;
use App\Services\DeviceService;
use App\Services\HolidayService;
use App\Services\EmployeeService;
use App\Services\DepartmentService;
use App\Repositories\BankRepository;
use App\Repositories\UserRepository;
use App\Services\DesignationService;
use App\Repositories\LeaveRepository;
use App\Repositories\DeviceRepository;
use App\Services\BankServiceInterface;
use App\Services\UserServiceInterface;
use App\Repositories\HolidayRepository;
use App\Services\LeaveServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EmployeeRepository;
use App\Services\DeviceServiceInterface;
use App\Services\HolidayServiceInterface;
use App\Repositories\DepartmentRepository;
use App\Services\EmployeeServiceInterface;
use App\Repositories\DesignationRepository;
use App\Services\DepartmentServiceInterface;
use App\Repositories\BankRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\DesignationServiceInterface;
use App\Repositories\LeaveRepositoryInterface;
use App\Repositories\DeviceRepositoryInterface;
use App\Repositories\HolidayRepositoryInterface;
use App\Repositories\EmployeeEducationRepository;
use App\Repositories\EmployeeRepositoryInterface;
use App\Repositories\DepartmentRepositoryInterface;
use App\Repositories\DesignationRepositoryInterface;
use App\Repositories\EmployeeEducationRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(DeviceRepositoryInterface::class, DeviceRepository::class);
        $this->app->bind(DeviceServiceInterface::class, DeviceService::class);

        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(DepartmentServiceInterface::class, DepartmentService::class);

        $this->app->bind(DesignationRepositoryInterface::class, DesignationRepository::class);
        $this->app->bind(DesignationServiceInterface::class, DesignationService::class);

        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(EmployeeServiceInterface::class, EmployeeService::class);

        $this->app->bind(EmployeeEducationRepositoryInterface::class, EmployeeEducationRepository::class
        );

        $this->app->bind(BankRepositoryInterface::class, BankRepository::class);
        $this->app->bind(BankServiceInterface::class, BankService::class);

        $this->app->bind(HolidayRepositoryInterface::class, HolidayRepository::class);
        $this->app->bind(HolidayServiceInterface::class, HolidayService::class);

          $this->app->bind(LeaveServiceInterface::class, LeaveService::class);
        $this->app->bind(LeaveRepositoryInterface::class, LeaveRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
