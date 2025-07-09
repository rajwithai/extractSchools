# Dominican Republic School Data Fetcher

**Test Job Project for Micole** - A Laravel Artisan command that fetches and processes school data from the official Dominican Republic open data portal.

## 📋 Project Overview

This Laravel project implements a robust data fetching system that:

- 🔍 **Discovers** the latest "Centros Educativos de República Dominicana" dataset using CKAN API
- ⬇️ **Downloads** the complete CSV data from datos.gob.do
- 🔄 **Processes** all school records with comprehensive field mapping and data normalization
- 💾 **Outputs** results to CSV or JSON files in `storage/app/`
- 📊 **Logs** processing statistics and completion details
- 🗄️ **Prepares** for database integration (marked as TODO pending schema confirmation)

## 🚀 Usage

### Basic Command

```bash
php artisan fetch:dominican-schools
```

### Command Options

```bash
# Output as JSON instead of CSV
php artisan fetch:dominican-schools --format=json

# Limit output for testing (0 = no limit, default)
php artisan fetch:dominican-schools --limit=100

# Show verbose processing details
php artisan fetch:dominican-schools --verbose

# Combine options
php artisan fetch:dominican-schools --format=json --limit=500 --verbose
```

## 📁 Project Structure

```
Dominican Republic School Data Fetcher/
├── app/
│   └── Console/
│       ├── Kernel.php                 # Registers the command
│       └── Commands/
│           └── FetchDominicanSchools.php    # Main command implementation
├── bootstrap/
│   └── app.php                        # Laravel application bootstrap
├── config/
│   └── app.php                        # Basic app configuration
├── database/
│   └── migrations/
│       └── 2024_12_27_000001_create_dominican_schools_table.php  # Proposed DB schema
├── routes/
│   └── console.php                    # Console routes
├── storage/
│   ├── app/                          # Output files location
│   └── logs/                         # Application logs
├── artisan                           # Laravel Artisan CLI
├── composer.json                     # Dependencies and project info
└── README.md                         # This file
```

## 🏗️ Architecture & Implementation

### Data Processing Pipeline

1. **Dataset Discovery**: Uses CKAN API to locate the most recent schools dataset
2. **Resource Selection**: Identifies and selects the latest CSV resource
3. **Data Download**: Retrieves the complete dataset with timeout handling
4. **CSV Processing**: Parses headers and processes each record with progress tracking
5. **Field Mapping**: Maps various field name variations to standardized schema
6. **Data Normalization**: Cleans and validates coordinates, numbers, dates, and text
7. **File Output**: Saves processed data as CSV or JSON with timestamps

### Key Features

- **Robust API Integration**: Handles CKAN API failures with fallback search strategies
- **Flexible Field Mapping**: Accommodates varying column names in source data
- **Data Validation**: Normalizes coordinates, enrollment numbers, years, and sectors
- **Progress Tracking**: Shows real-time processing progress with memory usage
- **Error Handling**: Gracefully handles missing data and parsing errors
- **Comprehensive Logging**: Records all activities for debugging and auditing

### Data Fields Extracted

| Field | Description | Normalization |
|-------|-------------|---------------|
| `id` / `code` | School identification code | Trimmed, validated |
| `name` | School name | Trimmed |
| `sector` | Public/Private/Semi-official | Standardized to Spanish terms |
| `education_level` | Education level/modality | Preserved as-is |
| `region` | Educational region | Geographic hierarchy |
| `district` | Educational district | Geographic hierarchy |
| `municipality` | Municipality/province | Geographic hierarchy |
| `address` | Physical address | Full text preserved |
| `latitude` / `longitude` | GPS coordinates | Validated, decimal conversion |
| `enrollment` | Student count | Numeric extraction |
| `year` | Data year | Validated range 1900-current+1 |
| `phone` | Contact phone | Preserved |
| `email` | Contact email | Preserved |
| `director` | School director | Preserved |
| `status` | Operational status | Preserved |

## 📊 Output Files

### File Naming Convention
- **CSV**: `dominican-schools-output-YYYY-MM-DD_HH-mm-ss.csv`
- **JSON**: `dominican-schools-output-YYYY-MM-DD_HH-mm-ss.json`

### File Locations
- **Development**: `storage/app/dominican-schools-output-*.{csv,json}`
- **Full Path**: Displayed in command output and logs

### CSV Format
```csv
"id","name","code","sector","education_level","region","district","municipality","address","latitude","longitude","enrollment","year","phone","email","director","status","processed_at"
"12345","Escuela Ejemplo","12345","Público","Básica","Region 01","Distrito 01-01","Santo Domingo","Calle Ejemplo 123","18.4861","-69.9312","250","2024","809-555-0123","ejemplo@minerd.gob.do","Juan Pérez","Activo","2024-12-27T10:30:00+00:00"
```

### JSON Format
```json
[
  {
    "id": "12345",
    "name": "Escuela Ejemplo",
    "code": "12345",
    "sector": "Público",
    "education_level": "Básica",
    "region": "Region 01",
    "district": "Distrito 01-01",
    "municipality": "Santo Domingo",
    "address": "Calle Ejemplo 123",
    "latitude": 18.4861,
    "longitude": -69.9312,
    "enrollment": 250,
    "year": 2024,
    "phone": "809-555-0123",
    "email": "ejemplo@minerd.gob.do",
    "director": "Juan Pérez",
    "status": "Activo",
    "raw_data": { ... },
    "processed_at": "2024-12-27T10:30:00+00:00"
  }
]
```

## 🗄️ Database Integration (TODO)

### Current Status
Database integration is **prepared but not implemented** pending schema confirmation. The code includes:

- ✅ Comprehensive migration file with proposed schema
- ✅ Placeholder `saveToDatabaseWhenReady()` method with example implementation
- ✅ Clear TODO markers indicating where database code would be activated

### Implementation Steps

1. **Review Schema**: Examine `database/migrations/2024_12_27_000001_create_dominican_schools_table.php`
2. **Run Migration**: `php artisan migrate` (after confirming schema)
3. **Create Model**: Generate `app/Models/School.php` with appropriate relationships
4. **Activate Database Code**: Uncomment the database integration in `FetchDominicanSchools.php`
5. **Test Integration**: Run command with database persistence enabled

### Proposed Database Schema

```sql
CREATE TABLE dominican_schools (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    sector ENUM('Público', 'Privado', 'Semi-oficial'),
    education_level VARCHAR(255),
    region VARCHAR(255),
    district VARCHAR(255),
    municipality VARCHAR(255),
    address TEXT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    enrollment INTEGER,
    year YEAR,
    phone VARCHAR(255),
    email VARCHAR(255),
    director VARCHAR(255),
    status VARCHAR(255),
    raw_data JSON,
    data_fetched_at TIMESTAMP,
    data_source VARCHAR(255) DEFAULT 'CKAN-datos.gob.do',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_code (code),
    INDEX idx_sector_region (sector, region),
    INDEX idx_education_municipality (education_level, municipality),
    INDEX idx_year_sector (year, sector)
);
```

## 🔧 Technical Requirements

### PHP Requirements
- PHP 8.1 or higher
- Laravel 10.x framework
- Guzzle HTTP client for API requests
- JSON extension for data processing

### Laravel Dependencies
```json
{
    "require": {
        "php": "^8.1",
        "guzzlehttp/guzzle": "^7.2",
        "laravel/framework": "^10.10",
        "laravel/sanctum": "^3.2",
        "laravel/tinker": "^2.8"
    }
}
```

### System Requirements
- Internet connection for CKAN API access
- Write permissions to `storage/app/` directory
- Sufficient memory for processing large datasets (typically 128MB+)

## 🛠️ Installation & Setup

### For Testing/Development

1. **Clone or extract project files**
2. **Install dependencies**: `composer install`
3. **Set permissions**: Ensure `storage/` is writable
4. **Run the command**: `php artisan fetch:dominican-schools`

### For Production Deployment

1. **Server setup** with PHP 8.1+ and Laravel requirements
2. **Environment configuration** with proper `.env` file
3. **Dependency installation** with `composer install --no-dev --optimize-autoloader`
4. **Schedule command** using Laravel's task scheduler if needed:
   ```php
   $schedule->command('fetch:dominican-schools')->dailyAt('02:00');
   ```

## 📈 Performance & Scalability

### Current Performance
- **Memory Usage**: Efficiently processes large datasets with progress tracking
- **Processing Speed**: Handles thousands of records with real-time progress display
- **Error Resilience**: Graceful handling of malformed data and network issues
- **Timeout Handling**: 120-second HTTP timeout with retry mechanisms

### Optimization Features
- **Progress Bars**: Visual feedback during long-running operations
- **Memory Monitoring**: Built-in memory usage tracking
- **Batch Processing**: Processes CSV line-by-line to minimize memory footprint
- **Efficient Data Structures**: Uses PHP arrays and built-in functions for optimal performance

## 🔍 Data Quality & Validation

### Validation Rules
- **Coordinates**: Must be valid decimal numbers within reasonable geographic bounds
- **Years**: Must be between 1900 and current year + 1
- **Numbers**: Enrollment and other numeric fields validated and cleaned
- **Required Fields**: Schools must have either a name or code to be included
- **Encoding**: Handles various character encodings and special characters

### Data Cleaning
- **Trimming**: All text fields trimmed of whitespace
- **Normalization**: Coordinates handle comma decimal separators
- **Standardization**: Sector values normalized to consistent Spanish terms
- **Error Handling**: Invalid records logged but don't stop processing

## 🔄 Extensibility & Maintenance

### Code Structure
- **Modular Design**: Each processing step is a separate method
- **Clear Documentation**: Comprehensive inline comments and docblocks
- **Error Handling**: Consistent exception handling throughout
- **Logging**: Detailed logs for debugging and monitoring

### Extension Points
- **Field Mappings**: Easy to add new field variations in `$fieldMappings` array
- **Output Formats**: Simple to add new output formats beyond CSV/JSON
- **Validation Rules**: Validation methods are isolated and easily modified
- **API Endpoints**: Can be adapted for other CKAN instances or APIs

### Following Micole Patterns
This implementation follows established patterns from other country scripts:
- **Consistent command structure** and naming conventions
- **Standardized field mapping** and normalization approaches
- **Common output format** and file naming patterns
- **Similar error handling** and logging strategies
- **Database integration preparation** following team standards

## 📋 Next Steps

### Immediate Actions
1. **Code Review**: Review implementation for adherence to Micole standards
2. **Testing**: Test with live data from datos.gob.do
3. **Output Validation**: Verify data quality and completeness in output files

### Database Integration
1. **Schema Confirmation**: Review and approve the proposed database schema
2. **Model Creation**: Create Laravel Eloquent model for schools
3. **Integration Testing**: Test database persistence functionality
4. **Performance Optimization**: Add database indexes and optimize queries

### Production Deployment
1. **Environment Setup**: Configure production environment
2. **Scheduling**: Set up automated execution schedule
3. **Monitoring**: Implement monitoring for successful execution
4. **Maintenance**: Establish procedures for handling API changes

## 🤝 Contributing

This project follows Micole's development standards:
- **Code Quality**: PSR-12 coding standards with comprehensive documentation
- **Testing**: Unit tests for critical data processing functions
- **Version Control**: Descriptive commit messages following team conventions
- **Error Handling**: Comprehensive exception handling with meaningful messages

## 📞 Support & Contact

For questions about this implementation or extending it for production use:
- **Code Review**: Submit for team review following Micole's processes
- **Documentation**: All implementation details documented in code comments
- **Extensibility**: Architecture designed for easy modification and enhancement

---

**Project Status**: ✅ **Complete** - Ready for code review and database integration when schema is confirmed.

**Last Updated**: December 27, 2024  
**Version**: 1.0.0  
**Author**: Micole Development Team 