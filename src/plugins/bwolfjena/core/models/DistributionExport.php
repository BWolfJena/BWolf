<?php namespace BwolfJena\Core\Models;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DistributionExport implements FromCollection, WithHeadings
{
    protected $distributionModuleId;
    protected $controller;

    public function headings(): array
    {
        return ['ID', 'Vorname', 'Nachname', 'Email', 'Kurs'];
    }

    public function __construct($distributionModuleId, $controller)
    {
        $this->distributionModuleId = $distributionModuleId;
        $this->controller = $controller;
    }

    public function collection()
    {
        $query = CourseUser::query();
        $this->controller->listExtendQuery($query);
        return $query->get();
    }
}
