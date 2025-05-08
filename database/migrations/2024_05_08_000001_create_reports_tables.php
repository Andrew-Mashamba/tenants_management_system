<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('occupancy_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->integer('total_units');
            $table->integer('occupied_units');
            $table->integer('vacant_units');
            $table->decimal('occupancy_rate', 5, 2);
            $table->json('unit_type_breakdown')->nullable(); // Breakdown by unit type
            $table->json('rental_income_breakdown')->nullable(); // Monthly rental income by unit type
            $table->timestamp('report_date');
            $table->timestamps();
        });

        Schema::create('rent_collection_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('total_rent_due', 10, 2);
            $table->decimal('total_rent_collected', 10, 2);
            $table->decimal('total_outstanding', 10, 2);
            $table->integer('total_tenants');
            $table->integer('paid_tenants');
            $table->integer('unpaid_tenants');
            $table->json('payment_breakdown')->nullable(); // Payment methods breakdown
            $table->json('late_payments')->nullable(); // Late payments details
            $table->timestamp('report_date');
            $table->timestamps();
        });

        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->integer('total_requests');
            $table->integer('completed_requests');
            $table->integer('pending_requests');
            $table->integer('overdue_requests');
            $table->decimal('total_cost', 10, 2);
            $table->json('category_breakdown')->nullable(); // Breakdown by maintenance category
            $table->json('vendor_breakdown')->nullable(); // Breakdown by vendor
            $table->json('response_time_metrics')->nullable(); // Average response times
            $table->timestamp('report_date');
            $table->timestamps();
        });

        Schema::create('lease_expiry_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->integer('expiring_this_month');
            $table->integer('expiring_next_month');
            $table->integer('expiring_in_three_months');
            $table->json('expiry_breakdown')->nullable(); // Detailed breakdown of expiring leases
            $table->json('renewal_status')->nullable(); // Renewal status tracking
            $table->timestamp('report_date');
            $table->timestamps();
        });

        Schema::create('financial_dashboard_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('total_revenue', 10, 2);
            $table->decimal('total_expenses', 10, 2);
            $table->decimal('net_income', 10, 2);
            $table->decimal('operating_expenses', 10, 2);
            $table->decimal('maintenance_costs', 10, 2);
            $table->decimal('vacancy_loss', 10, 2);
            $table->json('revenue_breakdown')->nullable(); // Breakdown by revenue source
            $table->json('expense_breakdown')->nullable(); // Breakdown by expense category
            $table->json('cash_flow_metrics')->nullable(); // Cash flow analysis
            $table->timestamp('report_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_dashboard_metrics');
        Schema::dropIfExists('lease_expiry_reports');
        Schema::dropIfExists('maintenance_reports');
        Schema::dropIfExists('rent_collection_reports');
        Schema::dropIfExists('occupancy_reports');
    }
}; 