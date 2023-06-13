<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function generateMigrationFromTable($tableName)
{
    // Get the table structure from the database
    $tableStructure = DB::select(DB::raw('SHOW CREATE TABLE ' . $tableName));

    // Get the CREATE TABLE query
    $createTableQuery = $tableStructure[0]->{'Create Table'};

    // Remove backticks and replace the table name with a :table placeholder
    $createTableQuery = str_replace('`', '', $createTableQuery);
    $createTableQuery = str_replace($tableName, ':table', $createTableQuery);

    // Generate a unique name for the migration
    $migrationName = "create_" . $tableName . "_table";
    $migrationFileName = date('Y_m_d_His') . '_' . $migrationName . '.php';

    // Define the migration class template
    $migrationClassTemplate = <<<'EOT'
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class :className extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            :createTableQuery
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(':table');
    }
}
EOT;

    // Replace placeholders with actual values
    $migrationClass = strtr($migrationClassTemplate, [
        ':className' => Str::studly($migrationName),
        ':createTableQuery' => $createTableQuery,
        ':table' => $tableName,
    ]);

    // Create a new migration file
    File::put(database_path('migrations/' . $migrationFileName), $migrationClass);
}

// Call the function for a specific table
generateMigrationFromTable('my_table');
