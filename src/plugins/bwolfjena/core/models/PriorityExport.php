<?php namespace BwolfJena\Core\Models;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PriorityExport implements FromCollection, WithHeadings
{
    protected $controller;

    public function headings(): array
    {
        return ['Vorname', 'Nachname', 'Email', 'Kurs', 'Präferenz'];
    }

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    public function collection()
    {
        $query = UserCoursePriority::query();
        $this->controller->listExtendQuery($query);
        return $query->get();
    }
}
