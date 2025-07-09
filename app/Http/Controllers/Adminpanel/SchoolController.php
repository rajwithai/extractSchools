<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\School;
use App\Models\Director;
use App\Models\Region;
use App\Models\City;
use App\Models\SchoolEducation;

class SchoolController extends Controller
{
    /**
     * Create a new school
     *
     * @param array $data
     * @param bool $autoGenerate
     * @param bool $createDirector
     * @return array
     */
    public function create($data, $autoGenerate = true, $createDirector = true)
    {
        try {
            DB::beginTransaction();

            // Validate required fields
            $validator = $this->validateSchoolData($data);
            if ($validator->fails()) {
                return [
                    'result' => false,
                    'code' => 101,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ];
            }

            // Check if school with this code already exists
            $existingSchool = School::where('code', $data['code'])->first();
            if ($existingSchool) {
                return [
                    'result' => false,
                    'code' => 102,
                    'message' => 'School with this code already exists'
                ];
            }

            // Check if email is already in use
            if (isset($data['accessemail'])) {
                $existingEmail = School::where('accessemail', $data['accessemail'])->first();
                if ($existingEmail) {
                    return [
                        'result' => false,
                        'code' => 106,
                        'message' => 'Email already in use'
                    ];
                }
            }

            // Create director first if needed
            $directorId = null;
            if ($createDirector) {
                $director = new Director();
                $director->name = $data['name'] ?? 'Director';
                $director->accessemail = $data['accessemail'] ?? null;
                $director->email = $data['accessemail'] ?? null;
                $director->phone = $data['phone'] ?? null;
                $director->password = bcrypt('temporal123'); // Temporary password
                $director->active = true;
                $director->save();
                
                $directorId = $director->id;
            }

            // Create school
            $school = new School();
            $school->code = $data['code'];
            $school->name = $data['name'];
            $school->latitude = $data['latitude'] ?? null;
            $school->longitude = $data['longitude'] ?? null;
            $school->accessemail = $data['accessemail'] ?? null;
            $school->email = $data['accessemail'] ?? null;
            $school->email2 = $data['email2'] ?? null;
            $school->phone = $data['phone'] ?? null;
            $school->website = $data['website'] ?? null;
            $school->scope_id = $data['scope_id'] ?? 1;
            $school->type_id = $data['type_id'] ?? null;
            $school->religion_id = $data['religion_id'] ?? 1;
            $school->religion_tipo_id = $data['religion_tipo_id'] ?? 1;
            $school->price_id = $data['price_id'] ?? 1;
            $school->segregation_id = $data['segregation_id'] ?? 1;
            $school->region_id = $data['region_id'] ?? null;
            $school->city_id = $data['city_id'] ?? null;
            $school->address = $data['address'] ?? null;
            $school->address_short = $data['address_short'] ?? null;
            $school->postal = $data['postal'] ?? null;
            $school->hidden = $data['hidden'] ?? false;
            $school->active = $data['active'] ?? true;
            $school->total_students = $data['total_students'] ?? null;
            $school->place_id = $data['place_id'] ?? null;
            $school->director_id = $directorId;
            $school->contact_id = $data['contact_id'] ?? null;
            $school->save();

            // Update director with school_id
            if ($createDirector && $directorId) {
                $director = Director::find($directorId);
                $director->school_id = $school->id;
                $director->save();
            }

            // Create education levels
            if (isset($data['educations']) && is_array($data['educations'])) {
                foreach ($data['educations'] as $education) {
                    $schoolEducation = new SchoolEducation();
                    $schoolEducation->school_id = $school->id;
                    $schoolEducation->maingroup_id = $education['maingroup_id'];
                    $schoolEducation->active = true;
                    $schoolEducation->save();
                }
            }

            DB::commit();

            return [
                'result' => true,
                'code' => 200,
                'message' => 'School created successfully',
                'school_id' => $school->id
            ];

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating school: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate school data
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateSchoolData($data)
    {
        $rules = [
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'accessemail' => 'nullable|email|max:255',
            'scope_id' => 'nullable|integer',
            'religion_id' => 'nullable|integer',
            'price_id' => 'nullable|integer',
            'region_id' => 'nullable|integer|exists:region,id',
            'city_id' => 'nullable|integer|exists:city,id',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Update school
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update($id, $data)
    {
        try {
            $school = School::find($id);
            if (!$school) {
                return [
                    'result' => false,
                    'code' => 404,
                    'message' => 'School not found'
                ];
            }

            $school->fill($data);
            $school->save();

            return [
                'result' => true,
                'code' => 200,
                'message' => 'School updated successfully'
            ];

        } catch (\Exception $e) {
            \Log::error('Error updating school: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error'
            ];
        }
    }

    /**
     * Get school by ID
     *
     * @param int $id
     * @return School|null
     */
    public function getSchool($id)
    {
        return School::with(['region', 'city', 'director', 'educations', 'services'])->find($id);
    }

    /**
     * Delete school
     *
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        try {
            $school = School::find($id);
            if (!$school) {
                return [
                    'result' => false,
                    'code' => 404,
                    'message' => 'School not found'
                ];
            }

            // Delete related records
            SchoolEducation::where('school_id', $id)->delete();
            
            // Delete director if exists
            if ($school->director_id) {
                Director::find($school->director_id)?->delete();
            }

            $school->delete();

            return [
                'result' => true,
                'code' => 200,
                'message' => 'School deleted successfully'
            ];

        } catch (\Exception $e) {
            \Log::error('Error deleting school: ' . $e->getMessage());
            
            return [
                'result' => false,
                'code' => 500,
                'message' => 'Internal server error'
            ];
        }
    }
} 