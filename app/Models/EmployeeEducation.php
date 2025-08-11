<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import the SoftDeletes trait

class EmployeeEducation extends Model
{
    use HasFactory, SoftDeletes; // Use the HasFactory and SoftDeletes traits

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_education'; // Explicitly define table name if it's not the plural of the model name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'degree_name',
        'field_of_study',
        'institute_name',
        'board',
        'passing_year',
        'gpa',
        'certificate_file',
        'created_by', // Don't forget these foreign keys
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'deleted_at', // Required for SoftDeletes
        // Add any other date fields if you have them, e.g., 'created_at', 'updated_at' are handled automatically.
    ];

    // If you need to cast attributes to specific types, you'd define them here.
    // protected $casts = [
    //     'passing_year' => 'integer', // Example: ensure passing_year is always an integer
    // ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the employee that owns the education record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who created the education record.
     */
    public function createdBy()
    {
        // Assuming your users table is named 'users' and the User model is App\Models\User
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the education record.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the education record (soft delete).
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators (Optional)
    |--------------------------------------------------------------------------
    */

    // Example accessor if you want to get the full URL of the certificate file
    // public function getCertificateFileUrlAttribute(): ?string
    // {
    //     if ($this->certificate_file) {
    //         return Storage::url($this->certificate_file);
    //     }
    //     return null;
    // }
}
