<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('miscs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('school_years', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('curriculum_managements', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('subject_managements', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('year_managements', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('section_managements', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('levels', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('commitment_payments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tuitions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('student_sms_taggings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('other_programs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('student_accounts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('selected_payment_types', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('selected_other_programs', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('cash_accounts', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('asset_inventories', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('buildings', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('rooms', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('receive_moneys', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('balance_sheets', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('profits_loss_statements', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('item_inventories', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('funds', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('sales', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('tax_codes', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('employees', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('spend_moneys', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('rfids', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('turnstile_logs', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('locker_inventories', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('fundin_transactions', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('enrollments', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('actions', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        // Schema::table('turnstiles', function (Blueprint $table) {
        //     $table->softDeletes();
        // });
        
        Schema::table('setup_grades', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('grade_templates', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('periods', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('departments', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('quarters', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('encode_grades', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('special_discounts', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('employment_statuses', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('leaves', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('transmutations', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('section_builders', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('section_builder_subject_management', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('other_services', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('requirements', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('smart_sms_tokens', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('smart_jwt_credentials', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('item_categories', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('item_inventory_quantity_logs', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('item_inventory_quantities', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('selected_other_services', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('asset_inventory_movements_logs', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Schema::table('students', function (Blueprint $table) {
        //     $table->softDeletes();
        // });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('miscs', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('school_years', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('curriculum_managements', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('subject_managements', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('year_managements', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('section_managements', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('commitment_payments', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('tuitions', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('student_sms_taggings', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('other_programs', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('student_accounts', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('selected_payment_types', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('payment_histories', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('selected_other_programs', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('cash_accounts', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('asset_inventories', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('receive_moneys', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('balance_sheets', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('profits_loss_statements', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('item_inventories', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('funds', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('tax_codes', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('spend_moneys', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('rfids', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('turnstile_logs', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('locker_inventories', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('fundin_transactions', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('turnstiles', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('setup_grades', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('grade_templates', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('quarters', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('encode_grades', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('special_discounts', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('employment_statuses', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('transmutations', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('section_builders', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('section_builder_subject_management', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('other_services', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('requirements', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('smart_sms_tokens', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('smart_jwt_credentials', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('item_categories', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('item_inventory_quantity_logs', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('item_inventory_quantities', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('selected_other_services', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });
        
        Schema::table('asset_inventory_movements_logs', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('deleted_at')->nullable();
        });

        
        
    }
}
