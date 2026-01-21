<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW vulnerabilities_list AS
            SELECT 
                v.id,
                v.pentest_id,
                v.display_order,
                v.description,
                v.criticality,
                v.is_resolved,
                v.resolved_at,
                v.is_visible,
                v.created_at,
                v.updated_at,
                p.application_name as pentest_name,
                CASE 
                    WHEN v.is_resolved = 1 THEN 'Mitigada'
                    ELSE 'Não Mitigada'
                END as status_text,
                CASE 
                    WHEN v.is_visible = 1 THEN 'Visível'
                    ELSE 'Oculta'
                END as visibility_text,
                CASE v.criticality
                    WHEN 'critical' THEN 'CRÍTICA'
                    WHEN 'high' THEN 'ALTA'
                    WHEN 'medium' THEN 'MÉDIA'
                    WHEN 'low' THEN 'BAIXA'
                    WHEN 'informative' THEN 'INFORMATIVA'
                    ELSE 'N/A'
                END as criticality_text,
                DATE_FORMAT(v.resolved_at, '%d/%m/%Y') as resolved_at_formatted,
                CASE v.criticality
                    WHEN 'critical' THEN 1
                    WHEN 'high' THEN 2
                    WHEN 'medium' THEN 3
                    WHEN 'low' THEN 4
                    WHEN 'informative' THEN 5
                    ELSE 999
                END as criticality_order,
                CASE 
                    WHEN v.is_resolved = 1 THEN 1
                    ELSE 2
                END as status_order
            FROM vulnerabilities v
            INNER JOIN pentests p ON v.pentest_id = p.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vulnerabilities_list');
    }
};
