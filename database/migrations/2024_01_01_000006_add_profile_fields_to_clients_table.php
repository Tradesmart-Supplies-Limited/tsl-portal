<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('name');
            $table->enum('client_type', ['individual', 'company'])->default('company')->after('logo_path');
            $table->string('industry')->nullable()->after('company');
            $table->string('website')->nullable()->after('industry');
            $table->string('tax_id')->nullable()->after('website'); // VAT / Tax / Registration number
            $table->string('secondary_email')->nullable()->after('email');
            $table->string('secondary_phone')->nullable()->after('phone');
            $table->string('postal_code')->nullable()->after('country');
            $table->string('source')->nullable()->after('postal_code'); // how the client was acquired
            $table->foreignId('account_manager_id')->nullable()->after('source')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('account_manager_id');
            $table->dropColumn([
                'logo_path', 'client_type', 'industry', 'website', 'tax_id',
                'secondary_email', 'secondary_phone', 'postal_code', 'source',
            ]);
        });
    }
};
