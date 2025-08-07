<?php
namespace App\Http\Controllers\DepartmentDesignation;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\DesignationServiceInterface;
use App\Http\Requests\DesignationStoreRequest;
use App\Http\Requests\DesignationUpdateRequest;
use App\Exceptions\DesignationNotFoundException;

class DesignationManageController extends Controller
{

    protected $designationService;
    protected $designationCrudLog;

    public function __construct(DesignationServiceInterface $designationService)
    {
        $this->designationService = $designationService;
        $this->designationCrudLog = Log::channel('designationStoreLog');
    }

    public function index()
    {
        $designations = $this->designationService->getAllDesignations();
        $departments = $this->designationService->getAllDepartments();


        return view('designations.index', compact('designations', 'departments'));
    }

    public function store(DesignationStoreRequest $request)
    {

        $designationName = $request->input('name');

        $this->designationCrudLog->info('Received Designation creation request', ['designation_name' => $designationName]);

        try {
            $designation = $this->designationService->createDesignation($request);

            $this->designationCrudLog->info('Designation created successfully', [
                'designation_name' => $designation->department_name,
                'designation_id'   => $designation->id,
            ]);

            return redirect()->route('designations.index')->with('success', 'designation created successfully!');
        } catch (\Exception $e) {
            $this->designationCrudLog->error('Failed to create designation', [
                'designation_name' => $designationName,
                'error_message'    => $e->getMessage(),
                'trace'            => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->withErrors([
                'error' => 'Failed to create designation: ' . $e->getMessage(),
            ]);
        }

    }

    public function edit(string $id)
    {
        try {
            $designation = $this->designationService->findDesignation($id);

            $departments = $this->designationService->getAllDepartments();

            return view('designations.edit', compact('designation', 'departments'));

        } catch (DesignationNotFoundException $e) {
            return redirect()->route('designations.index')->withErrors([
                'error' => $e->getMessage(),
            ]);

        } catch (\Exception $e) {
            $this->designationCrudLog->error("Failed to retrieve Designaton for editing", [
                'designation_id' => $id,
                'error_message'  => $e->getMessage(),
            ]);

            return redirect()->route('designations.index')->withErrors([
                'error' => 'Failed to retrieve designation for editing: ' . $e->getMessage(),
            ]);
        }
    }


    public function update(DesignationUpdateRequest $request, string $id)
    {
        // dd($request->all());

        $this->designationCrudLog->info('Received designation update request', ['designation_id' => $id]);

        try {
            $designation = $this->designationService->updateDesignation($request, $id);

            $this->designationCrudLog->info('Designation updated successfully', [
                'designation_id' => $designation->id,
                'name'           => $designation->name,
            ]);

            return redirect()->route('designations.index')->with('success', 'Designation updated successfully!');

        } catch (DesignationNotFoundException $e) {
            return redirect()->route('designations.index')->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            $this->designationCrudLog->error('Failed to update designation', [
                'designation_id'  => $id,
                'error_message'   => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->withErrors([
                'error' => 'Failed to update designation: ' . $e->getMessage(),
            ]);
        }
    }


     public function destroy(string $id)
    {
        $this->designationCrudLog->info('Received designation deletion request', ['designation_id' => $id]);

        try {
            $this->designationService->deleteDesignation($id);

            $this->designationCrudLog->info('Designation deleted successfully', ['designation_id' => $id]);

            return redirect()->route('designations.index')->with('success', 'Designation deleted successfully!');

        } catch (DesignationNotFoundException $e) {
            return redirect()->route('designations.index')->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            $this->designationCrudLog->error('Failed to delete designation', [
                'designation_id'  => $id,
                'error_message'   => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);

            return redirect()->route('designations.index')->withErrors([
                'error' => 'Failed to delete designation: ' . $e->getMessage(),
            ]);
        }
    }

}
