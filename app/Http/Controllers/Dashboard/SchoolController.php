<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolEducation;

class SchoolController extends Controller
{
    /**
     * Set type ID for school based on education levels
     *
     * @param int $schoolId
     * @return void
     */
    public function setTypeID($schoolId)
    {
        $school = School::find($schoolId);
        if (!$school) {
            return;
        }

        $educations = SchoolEducation::where('school_id', $schoolId)
            ->pluck('maingroup_id')
            ->toArray();

        if (empty($educations)) {
            return;
        }

        // Determine school type based on education levels
        // Following the pattern from client's other implementations
        $typeId = $this->determineSchoolType($educations);
        
        $school->type_id = $typeId;
        $school->save();
    }

    /**
     * Determine school type based on education levels
     *
     * @param array $educations
     * @return int
     */
    private function determineSchoolType($educations)
    {
        $hasInfantil = in_array(1, $educations);      // Infantil/Preescolar
        $hasPrimaria = in_array(2, $educations);      // Primaria
        $hasSecundaria = in_array(3, $educations);    // Secundaria
        $hasGuarderia = in_array(6, $educations);     // Guardería
        $hasSuperior = in_array(7, $educations);      // Superior/FP

        // Logic similar to client's Colombian implementation
        if ($hasInfantil && !$hasPrimaria && !$hasSecundaria) {
            return 1; // Solo infantil
        } elseif ($hasInfantil && $hasPrimaria && !$hasSecundaria) {
            return 2; // Infantil + Primaria
        } elseif ($hasInfantil && $hasPrimaria && $hasSecundaria) {
            return 3; // Completo (Infantil + Primaria + Secundaria)
        } elseif (!$hasInfantil && !$hasPrimaria && $hasSecundaria) {
            return 4; // Solo secundaria
        } elseif ($hasGuarderia) {
            return 6; // Guardería
        } elseif ($hasSuperior) {
            return 7; // Superior/Universitario
        } elseif ($hasPrimaria && $hasSecundaria) {
            return 3; // Primaria + Secundaria
        } elseif ($hasPrimaria) {
            return 2; // Solo primaria
        } else {
            return 46; // Otros/Sin clasificar
        }
    }

    /**
     * Set school metadata
     *
     * @param int $schoolId
     * @return void
     */
    public function setSchoolMetaData($schoolId)
    {
        $school = School::find($schoolId);
        if (!$school) {
            return;
        }

        // Additional metadata processing can be added here
        // This method is referenced in client's implementations
        
        // Update any additional calculated fields
        $this->updateCalculatedFields($school);
    }

    /**
     * Update calculated fields for school
     *
     * @param School $school
     * @return void
     */
    private function updateCalculatedFields($school)
    {
        // Calculate derived data based on school information
        // This could include things like:
        // - Setting default values based on location
        // - Calculating scores or ratings
        // - Setting status flags
        
        $school->save();
    }

    /**
     * Get school dashboard data
     *
     * @param int $schoolId
     * @return array
     */
    public function getDashboardData($schoolId)
    {
        $school = School::with([
            'region',
            'city', 
            'director',
            'educations',
            'services'
        ])->find($schoolId);

        if (!$school) {
            return null;
        }

        return [
            'school' => $school,
            'statistics' => $this->getSchoolStatistics($school),
            'metadata' => $this->getSchoolMetadata($school)
        ];
    }

    /**
     * Get school statistics
     *
     * @param School $school
     * @return array
     */
    private function getSchoolStatistics($school)
    {
        return [
            'total_students' => $school->total_students ?? 0,
            'education_levels' => $school->educations->count(),
            'services_count' => $school->services->count(),
            'has_coordinates' => !is_null($school->latitude) && !is_null($school->longitude),
            'has_contact_info' => !is_null($school->phone) || !is_null($school->accessemail),
        ];
    }

    /**
     * Get school metadata
     *
     * @param School $school
     * @return array
     */
    private function getSchoolMetadata($school)
    {
        return [
            'type_name' => $this->getTypeName($school->type_id),
            'scope_name' => $this->getScopeName($school->scope_id),
            'price_name' => $this->getPriceName($school->price_id),
            'religion_name' => $this->getReligionName($school->religion_id),
            'region_name' => $school->region->name ?? null,
            'city_name' => $school->city->name ?? null,
        ];
    }

    /**
     * Get type name by ID
     */
    private function getTypeName($typeId)
    {
        $types = [
            1 => 'Infantil',
            2 => 'Primaria',
            3 => 'Completo',
            4 => 'Secundaria',
            6 => 'Guardería',
            7 => 'Superior',
            46 => 'Otros'
        ];

        return $types[$typeId] ?? 'No definido';
    }

    /**
     * Get scope name by ID
     */
    private function getScopeName($scopeId)
    {
        $scopes = [
            1 => 'Público',
            2 => 'Concertado',
            3 => 'Privado'
        ];

        return $scopes[$scopeId] ?? 'No definido';
    }

    /**
     * Get price name by ID
     */
    private function getPriceName($priceId)
    {
        $prices = [
            1 => 'Gratuito',
            2 => 'Bajo (1-25k)',
            3 => 'Medio (25-100k)',
            4 => 'Alto (>100k)'
        ];

        return $prices[$priceId] ?? 'No definido';
    }

    /**
     * Get religion name by ID
     */
    private function getReligionName($religionId)
    {
        $religions = [
            1 => 'Laico',
            2 => 'Católico',
            3 => 'Protestante',
            4 => 'Judío',
            5 => 'Musulmán',
            6 => 'Budista',
            7 => 'Otros'
        ];

        return $religions[$religionId] ?? 'No definido';
    }
} 