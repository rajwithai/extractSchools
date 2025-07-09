<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for Dominican Republic Schools Table
 * 
 * This is a PROPOSED database schema that would be used when database integration
 * is confirmed. The FetchDominicanSchools command is currently configured to output
 * to files only, with database integration clearly marked as TODO.
 * 
 * To implement database integration:
 * 1. Review and confirm this schema matches requirements
 * 2. Run: php artisan migrate
 * 3. Uncomment the saveToDatabaseWhenReady() method in FetchDominicanSchools command
 * 4. Create the School model (app/Models/School.php)
 */
class CreateDominicanSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dominican_schools', function (Blueprint $table) {
            $table->id();
            
            // Core identification fields
            $table->string('code')->unique()->index()->comment('Official school code from MINERD');
            $table->string('name')->index()->comment('School name');
            
            // Administrative classification
            $table->enum('sector', ['Público', 'Privado', 'Semi-oficial'])->nullable()->index()->comment('School sector type');
            $table->string('education_level')->nullable()->index()->comment('Education level (Inicial, Básica, Media, etc.)');
            
            // Geographic hierarchy
            $table->string('region')->nullable()->index()->comment('Educational region');
            $table->string('district')->nullable()->index()->comment('Educational district');
            $table->string('municipality')->nullable()->index()->comment('Municipality');
            $table->text('address')->nullable()->comment('Full address');
            
            // Geographic coordinates
            $table->decimal('latitude', 10, 8)->nullable()->index()->comment('GPS latitude');
            $table->decimal('longitude', 11, 8)->nullable()->index()->comment('GPS longitude');
            
            // Operational data
            $table->integer('enrollment')->nullable()->comment('Student enrollment count');
            $table->year('year')->nullable()->index()->comment('Data year');
            $table->string('phone')->nullable()->comment('Contact phone');
            $table->string('email')->nullable()->comment('Contact email');
            $table->string('director')->nullable()->comment('School director name');
            $table->string('status')->nullable()->comment('Operational status');
            
            // Metadata and auditing
            $table->json('raw_data')->nullable()->comment('Original raw data from CSV for reference');
            $table->timestamp('data_fetched_at')->nullable()->comment('When data was fetched from API');
            $table->string('data_source')->default('CKAN-datos.gob.do')->comment('Source of the data');
            $table->timestamps();
            
            // Indexes for common queries
            $table->index(['sector', 'region']);
            $table->index(['education_level', 'municipality']);
            $table->index(['year', 'sector']);
            
            // Spatial index for geographic queries (if supported)
            // $table->spatialIndex(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dominican_schools');
    }
}

/*
 * Additional Tables for Normalized Structure (Optional Enhancement):
 * 
 * If further normalization is desired, consider these additional tables:
 * 
 * - educational_regions (id, name, code)
 * - educational_districts (id, region_id, name, code)  
 * - municipalities (id, district_id, name, province)
 * - school_types (id, name, description)
 * - education_levels (id, name, description, order)
 * 
 * This would allow for:
 * - Better data integrity
 * - Easier reporting and analytics
 * - Hierarchical administrative queries
 * - Reference data management
 */ 