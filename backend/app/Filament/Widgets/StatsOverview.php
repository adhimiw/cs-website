<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Lead;
use App\Models\ContactSubmission;
use App\Models\PageVisit;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalLeads = Lead::count();
        $qualifiedLeads = Lead::where('lead_status', 'qualified')->count();
        $totalSubmissions = ContactSubmission::count();
        $totalVisits = PageVisit::count();

        return [
            Stat::make('Total CRM Leads', $totalLeads)
                ->description($qualifiedLeads . ' qualified leads')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Form Submissions', $totalSubmissions)
                ->description('Contact Us form')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),
            Stat::make('Total Page Visits', $totalVisits)
                ->description('Unique IP & UTM tracked')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('primary'),
        ];
    }
}
